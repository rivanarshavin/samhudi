document.addEventListener('DOMContentLoaded', () => {
    const treeContainer = document.getElementById('treeContainer');
    
    // Fetch and render tree
    fetch(treeApiUrl)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                treeContainer.innerHTML = `<div class="empty-state">${data.error}</div>`;
                return;
            }
            
            // Flatten tree by depth
            const generations = {};
            
            function traverse(node, depth) {
                if (!generations[depth]) generations[depth] = [];
                generations[depth].push(node);
                
                if (node.children && node.children.length > 0) {
                    node.children.forEach(child => traverse(child, depth + 1));
                }
            }
            
            traverse(data, 0);
            renderGenerations(generations);
        })
        .catch(err => {
            treeContainer.innerHTML = `<div class="empty-state">Gagal memuat data silsilah.</div>`;
            console.error(err);
        });

    function renderGenerations(generations) {
        treeContainer.innerHTML = '';
        
        const genKeys = Object.keys(generations).map(Number).sort((a,b)=>a-b);
        
        genKeys.forEach(depth => {
            const members = generations[depth];
            
            // Determine Generation Name
            let genName = 'Generasi ' + (depth + 1);
            let genSub = '';
            if (depth === 0) genSub = 'Keluarga Pendiri';
            else if (depth === 1) genSub = 'Anak';
            else if (depth === 2) genSub = 'Cucu';
            else if (depth === 3) genSub = 'Cicit';
            else if (depth === 4) genSub = 'Piut';
            
            const totalIndividu = members.reduce((acc, m) => acc + 1 + (m.pasangan ? 1 : 0), 0);
            
            const rowHtml = `
                <div class="generation-row">
                    <div class="generation-info">
                        <div>
                            <h3 class="gen-title">${genName}</h3>
                            <p class="gen-subtitle">${genSub}</p>
                            <div class="gen-stats">
                                <i class="bi bi-people"></i> ${members.length} Keluarga
                            </div>
                            <div class="gen-stats" style="margin-top:4px;">
                                <i class="bi bi-person"></i> ${totalIndividu} Individu
                            </div>
                        </div>
                    </div>
                    <div class="generation-cards">
                        ${members.map(m => renderMemberCard(m)).join('')}
                    </div>
                </div>
            `;
            
            treeContainer.insertAdjacentHTML('beforeend', rowHtml);
        });

        // Add event listeners to cards
        document.querySelectorAll('.member-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openModal(id);
            });
        });
        
        document.querySelectorAll('.clickable-profile').forEach(col => {
            col.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.getAttribute('data-id');
                if (id) openModal(id);
            });
        });
    }

    function renderMemberCard(member) {
        let profilesHtml = '';
        
        profilesHtml += `
            <div class="profile-col clickable-profile" data-id="${member.id}">
                <img src="${member.foto}" class="profile-img" alt="${member.nama}">
                <div class="profile-name">${member.nama.split(' ')[0]}</div>
            </div>
        `;
        
        if (member.pasangan) {
            if (Array.isArray(member.pasangan)) {
                member.pasangan.forEach(p => {
                    profilesHtml += `
                        <div class="profile-col clickable-profile" data-id="${p.id}">
                            <img src="${p.foto}" class="profile-img" alt="${p.nama}">
                            <div class="profile-name">${p.nama.split(' ')[0]}</div>
                        </div>
                    `;
                });
            } else {
                profilesHtml += `
                    <div class="profile-col clickable-profile" data-id="${member.pasangan.id}">
                        <img src="${member.pasangan.foto}" class="profile-img" alt="${member.pasangan.nama}">
                        <div class="profile-name">${member.pasangan.nama.split(' ')[0]}</div>
                    </div>
                `;
            }
        }
        
        const anakCount = member.children ? member.children.length : 0;
        const footerHtml = `<div class="card-footer"><i class="bi bi-people"></i> ${anakCount} Anak <i class="bi bi-chevron-right" style="font-size:10px; margin-left:4px;"></i></div>`;
        
        return `
            <div class="member-card" data-id="${member.id}">
                <div class="member-card-profiles">
                    ${profilesHtml}
                </div>
                ${footerHtml}
            </div>
        `;
    }

    // Modal Logic
    const modal = document.getElementById('infoPopup');
    const btnClose = document.getElementById('popupClose');
    const btnTutup = document.getElementById('btnTutupModal');
    
    function closeModal() {
        modal.classList.remove('open');
    }
    
    btnClose.addEventListener('click', closeModal);
    btnTutup.addEventListener('click', closeModal);
    
    // Close on click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Tab Logic
    const tabs = document.querySelectorAll('.modal-tab');
    const panes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));
            
            // Add active to clicked
            tab.classList.add('active');
            const target = tab.getAttribute('data-target');
            document.getElementById(target).classList.add('active');
        });
    });

    function openModal(id) {
        // Show loading state (optional)
        document.getElementById('modalName').innerText = 'Memuat...';
        modal.classList.add('open');
        
        // Reset tabs to first tab
        tabs[0].click();

        fetch(detailApiUrl + '?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    closeModal();
                    return;
                }
                populateModal(data);
            })
            .catch(err => {
                console.error(err);
                alert("Gagal memuat detail.");
                closeModal();
            });
    }

    function populateModal(data) {
        // Header
        document.getElementById('modalPhoto').src = data.foto;
        document.getElementById('modalName').innerText = data.nama;
        document.getElementById('modalGenerationLabel').innerText = `${data.generasi_label} • ${data.gender === 'L' ? 'Laki-laki' : 'Perempuan'}`;

        // Tab Individu
        const infoItems = [
            { icon: 'geo-alt', label: 'Tempat Lahir', value: data.tempat_lahir || '-' },
            { icon: 'gender-ambiguous', label: 'Jenis Kelamin', value: data.gender === 'L' ? 'Laki-laki' : 'Perempuan' },
            { icon: 'briefcase', label: 'Pekerjaan', value: data.pekerjaan || '-' },
            { icon: 'moon-stars', label: 'Agama', value: data.agama || '-' },
            { icon: 'person', label: 'Ayah', value: data.ayah_name || '-' },
            { icon: 'person', label: 'Ibu', value: data.ibu_name || '-' },
            { icon: 'heart', label: data.pasangan_label || 'Pasangan', value: data.pasangan_name || '-' },
            { icon: 'people', label: 'Anak', value: data.jumlah_anak || '-' },
            { icon: 'telephone', label: 'No. Telp', value: data.telepon || '-' },
            { icon: 'envelope', label: 'Email', value: data.email || '-' },
            { icon: 'activity', label: 'Status', value: data.status || '-' },
            { icon: 'house', label: 'Alamat', value: data.tempat_tinggal || '-' }
        ];

        let htmlIndividu = '';
        infoItems.forEach(item => {
            htmlIndividu += `
                <div class="info-label"><i class="bi bi-${item.icon}"></i> ${item.label}</div>
                <div class="info-value">${item.value}</div>
            `;
        });
        document.getElementById('infoListIndividu').innerHTML = htmlIndividu;

        // Tab Keluarga
        let htmlKeluargaInfo = '';
        if (data.generasi === 1 || data.generasi === 2) {
            // For parents, show spouse and children count at top
            htmlKeluargaInfo += `
                <div class="info-label"><i class="bi bi-person"></i> Nama ${data.pasangan_label || 'Pasangan'}</div>
                <div class="info-value">${data.pasangan_name || '-'}</div>
                <div class="info-label"><i class="bi bi-people"></i> Jumlah Anak</div>
                <div class="info-value">${data.jumlah_anak || '-'}</div>
            `;
        } else {
            // For children, show order and parents
            htmlKeluargaInfo += `
                <div class="info-label"><i class="bi bi-sort-numeric-down"></i> Anak ke</div>
                <div class="info-value">${data.anak_ke || '-'}</div>
                <div class="info-label"><i class="bi bi-people"></i> Dari (jumlah)</div>
                <div class="info-value">${data.dari_jumlah_saudara || '-'} bersaudara</div>
                <div class="info-label"><i class="bi bi-person"></i> Ayah</div>
                <div class="info-value">${data.ayah_name || '-'}</div>
                <div class="info-label"><i class="bi bi-person"></i> Ibu</div>
                <div class="info-value">${data.ibu_name || '-'}</div>
            `;
        }
        document.getElementById('infoListKeluargaInfo').innerHTML = htmlKeluargaInfo;

        // Family Cards (Istri, Anak, Ortua, Saudara)
        let htmlCards = '';
        
        if (data.pasangan && data.pasangan.length > 0) {
            htmlCards += renderSubCardGroup(data.pasangan_label || 'Pasangan', data.pasangan);
        }
        if (data.anak_anak && data.anak_anak.length > 0) {
            htmlCards += renderSubCardGroup('Anak-anak', data.anak_anak);
        }
        if (data.orang_tua && data.orang_tua.length > 0) {
            htmlCards += renderSubCardGroup('Orang Tua', data.orang_tua);
        }
        if (data.saudara && data.saudara.length > 0) {
            htmlCards += renderSubCardGroup('Saudara', data.saudara);
        }

        document.getElementById('familyCardsSection').innerHTML = htmlCards;
    }

    function renderSubCardGroup(title, membersArray) {
        let html = `<div class="sub-card-group"><div class="sub-card-title">${title}</div>`;
        membersArray.forEach(m => {
            html += `
                <div class="sub-card">
                    <img src="${m.foto}" alt="${m.nama}">
                    <div class="sub-card-info">
                        <h4>${m.nama}</h4>
                        <p>${m.generasi_info || m.hubungan}</p>
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        return html;
    }
});