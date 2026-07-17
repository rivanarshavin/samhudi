let currentStep = 0;
let selectedRole = '';
let selectedRelIds = []; // Array of {id, name, gender}
let selectedRelGender = '';
let newMemberId = null;

function showRelationError(elementId, msg) {
    const el = document.getElementById(elementId);
    if (!el) return;
    el.innerText = msg;
    el.style.display = 'block';
    setTimeout(() => {
        el.style.display = 'none';
    }, 3500);
}

// Step Navigation
function nextStep(stepIndex) {
    if (stepIndex === 1) {
        document.getElementById('wizardHeader').style.display = 'flex';
    }
    
    // Update Header Titles based on step
    if (stepIndex === 1) {
        document.getElementById('stepTitle').innerText = 'Siapa Kamu?';
    } else if (stepIndex === 2) {
        document.getElementById('stepTitle').innerText = 'Generasi';
    } else if (stepIndex === 3) {
        document.getElementById('stepTitle').innerText = 'Hubungan';
        // Setup question text based on role
        if (selectedRole === 'anak') {
            document.getElementById('relationQuestion').innerText = 'Siapa orang tua kamu?';
        } else if (selectedRole === 'pasangan') {
            document.getElementById('relationQuestion').innerText = 'Siapa pasangan kamu?';
        } else if (selectedRole === 'orangtua') {
            document.getElementById('relationQuestion').innerText = 'Siapa anak kamu?';
        }
        // Focus search box
        setTimeout(() => document.getElementById('searchMember').focus(), 100);
    } else if (stepIndex === 4) {
        document.getElementById('stepTitle').innerText = 'Data Diri';
        setupGenderLock();
    }

    // Hide all steps, show current
    document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
    document.getElementById('step' + stepIndex).classList.add('active');
    
    // Update step indicator
    if (stepIndex > 0) {
        document.querySelector('.step-num').innerText = stepIndex;
        // Update leaves
        for (let i = 1; i <= 4; i++) {
            const leaf = document.getElementById('leaf' + i);
            if (leaf) {
                if (i <= stepIndex) leaf.classList.add('active');
                else leaf.classList.remove('active');
            }
        }
    }
    
    currentStep = stepIndex;
}

function prevStep(stepIndex) {
    nextStep(stepIndex);
}

function enableNext(step) {
    if (step === 1) {
        const checked = document.querySelector('input[name="role"]:checked');
        if (checked) {
            selectedRole = checked.value;
            document.getElementById('btnNext1').disabled = false;
        }
    } else if (step === 2) {
        const gen = document.getElementById('generasi').value;
        if (gen) {
            document.getElementById('btnNext2').disabled = false;
        } else {
            document.getElementById('btnNext2').disabled = true;
        }
    }
}

// Search Logic
let searchTimeout;
function searchMember(term) {
    clearTimeout(searchTimeout);
    const listEl = document.getElementById('memberList');
    
    if (term.length < 2) {
        listEl.innerHTML = '<div style="text-align:center; color:gray; font-size:12px; padding:10px;">Ketik minimal 2 huruf</div>';
        return;
    }
    
    listEl.innerHTML = '<div style="text-align:center; color:gray; font-size:12px; padding:10px;">Mencari...</div>';
    
    searchTimeout = setTimeout(() => {
        fetch(searchApiUrl + '?term=' + encodeURIComponent(term))
            .then(res => res.json())
            .then(data => {
                listEl.innerHTML = '';
                
                data.forEach(item => {
                    const isSelected = selectedRelIds.some(r => r.id == item.id);
                    const html = `
                        <label class="member-item">
                            <img src="https://placehold.co/40x40/CBD9CF/4A6055?text=${item.full_name.charAt(0)}" alt="">
                            <div style="flex:1">
                                <strong style="font-size:14px; display:block; color:var(--ink);">${item.full_name}</strong>
                                <span style="font-size:11px; color:var(--ink-soft);">${item.gender === 'L' ? 'Laki-laki' : 'Perempuan'}</span>
                            </div>
                            <input type="checkbox" name="rel_id_search" value="${item.id}" data-gender="${item.gender}" data-name="${item.full_name}" onchange="selectRelation(this)" ${isSelected ? 'checked' : ''}>
                        </label>
                    `;
                    listEl.insertAdjacentHTML('beforeend', html);
                });

                // Tambahkan opsi "Tambah Baru" di akhir list
                const addHtml = `
                    <div class="member-item" style="border: 2px dashed var(--forest-deep); cursor: pointer; justify-content: center; background: var(--input-bg);" onclick="promptNewRelative('${term}')">
                        <div style="text-align: center; color: var(--forest-deep);">
                            <i class="bi bi-plus-circle-fill"></i> Tambah "<strong>${term}</strong>"
                        </div>
                    </div>
                `;
                listEl.insertAdjacentHTML('beforeend', addHtml);

            })
            .catch(err => {
                listEl.innerHTML = '<div style="text-align:center; color:red; font-size:12px; padding:10px;">Error pencarian.</div>';
            });
    }, 500);
}

function promptNewRelative(name = '') {
    // Reset dan atur input nama
    document.getElementById('newRelNameInput').value = name;
    document.getElementById('newRelNameError').style.display = 'none';
    
    // Reset gender selection
    document.getElementById('newRelGenderVal').value = '';
    document.getElementById('newRelGenderError').style.display = 'none';
    document.getElementById('btnMale').style.borderColor = '#e2e8e5';
    document.getElementById('btnMale').style.background = 'white';
    document.getElementById('btnFemale').style.borderColor = '#e2e8e5';
    document.getElementById('btnFemale').style.background = 'white';
    
    // Reset form pencarian orang tua
    clearSelectedParent();
    document.getElementById('parentSearch').value = '';
    
    document.getElementById('newRelModal').style.display = 'flex';
}

function closeNewRelModal() {
    document.getElementById('newRelModal').style.display = 'none';
}

function selectGender(gender) {
    document.getElementById('newRelGenderVal').value = gender;
    document.getElementById('newRelGenderError').style.display = 'none';
    
    const btnMale = document.getElementById('btnMale');
    const btnFemale = document.getElementById('btnFemale');
    
    if (gender === 'L') {
        btnMale.style.borderColor = '#0288d1';
        btnMale.style.background = '#f0f8ff';
        btnFemale.style.borderColor = '#e2e8e5';
        btnFemale.style.background = 'white';
    } else {
        btnFemale.style.borderColor = '#c2185b';
        btnFemale.style.background = '#fff0f5';
        btnMale.style.borderColor = '#e2e8e5';
        btnMale.style.background = 'white';
    }
}

function submitNewRelative() {
    const nameInput = document.getElementById('newRelNameInput');
    const name = nameInput.value.trim();
    
    if (!name) {
        document.getElementById('newRelNameError').style.display = 'block';
        nameInput.focus();
        return;
    }
    
    const gender = document.getElementById('newRelGenderVal').value;
    if (!gender) {
        document.getElementById('newRelGenderError').style.display = 'block';
        return;
    }
    
    const generasi = document.getElementById('newRelGenerasi').value;
    if (!generasi) {
        document.getElementById('newRelGenerasiError').style.display = 'block';
        return;
    }

    if (selectedRole === 'anak') {
        const sameGenderExists = selectedRelIds.some(r => r.gender === gender);
        if (sameGenderExists) {
            showRelationError('newRelParentError', 'Anda hanya bisa memilih satu ' + (gender === 'L' ? 'Ayah' : 'Ibu') + '!');
            return;
        }
        if (selectedRelIds.length >= 2) {
            showRelationError('newRelParentError', 'Maksimal hanya bisa memilih 2 orang tua!');
            return;
        }
    }
    
    const parentId = document.getElementById('selectedParentId').value;
    
    let id = 'new_' + encodeURIComponent(name) + '_' + gender + '_' + generasi;
    if (parentId) {
        id += '_' + parentId;
    } else {
        id += '_0'; // eksplisit pass 0 jika tidak ada parent
    }
    
    if (!selectedRelIds.some(r => r.id == id)) {
        selectedRelIds.push({id: id, name: name, gender: gender});
    }
    
    updateSelectedTags();
    closeNewRelModal();
    
    // Kosongkan pencarian utama
    document.getElementById('searchMember').value = '';
    document.getElementById('memberList').innerHTML = '';
}

function selectParent(id, name) {
    document.getElementById('selectedParentId').value = id;
    document.getElementById('selectedParentName').innerText = name;
    document.getElementById('selectedParentContainer').style.display = 'flex';
    document.getElementById('parentSearch').style.display = 'none';
    document.getElementById('parentSearchResult').style.display = 'none';
    document.getElementById('parentSearch').value = '';
}

function clearSelectedParent() {
    document.getElementById('selectedParentId').value = '';
    document.getElementById('selectedParentName').innerText = '';
    document.getElementById('selectedParentContainer').style.display = 'none';
    document.getElementById('parentSearch').style.display = 'block';
    document.getElementById('parentSearchResult').style.display = 'none';
}

// Event Listeners diletakkan setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    // Parent search logic
    const parentSearch = document.getElementById('parentSearch');
    let parentSearchTimeout;
    if (parentSearch) {
        parentSearch.addEventListener('input', function() {
            clearTimeout(parentSearchTimeout);
            const term = this.value.trim();
            const resEl = document.getElementById('parentSearchResult');
            
            if (term.length < 2) {
                resEl.style.display = 'none';
                return;
            }
            
            parentSearchTimeout = setTimeout(() => {
                fetch(searchApiUrl + '?term=' + encodeURIComponent(term))
                    .then(r => r.json())
                    .then(res => {
                        resEl.innerHTML = '';
                        if (res.length === 0) {
                            resEl.innerHTML = '<div style="padding:10px; color:var(--ink-soft); font-size:13px; text-align:center;">Tidak ditemukan</div>';
                        } else {
                            res.forEach(item => {
                                const div = document.createElement('div');
                                div.style.cssText = 'padding:10px; cursor:pointer; border-bottom:1px solid var(--line); display:flex; flex-direction:column; background:var(--input-bg);';
                                div.innerHTML = `
                                    <span style="font-weight:600; font-size:14px; color:var(--ink);">${item.full_name}</span>
                                    <span style="font-size:11px; color:var(--ink-soft);">${item.gender === 'L' ? 'Laki-laki' : 'Perempuan'}</span>
                                `;
                                div.onmouseover = () => div.style.background = 'var(--cream)';
                                div.onmouseout = () => div.style.background = 'var(--input-bg)';
                                div.onclick = () => selectParent(item.id, item.full_name);
                                resEl.appendChild(div);
                            });
                        }
                        resEl.style.display = 'block';
                    })
                    .catch(() => {
                        resEl.innerHTML = '<div style="padding:10px; color:red; font-size:12px; text-align:center;">Error</div>';
                        resEl.style.display = 'block';
                    });
            }, 500);
        });
        
        // Hide result on click outside
        document.addEventListener('click', (e) => {
            if (!parentSearch.contains(e.target) && !document.getElementById('parentSearchResult').contains(e.target)) {
                document.getElementById('parentSearchResult').style.display = 'none';
            }
        });
    }
});

function selectRelation(input) {
    const id = input.value;
    const gender = input.getAttribute('data-gender');
    const name = input.getAttribute('data-name');
    
    // Jika peran sebagai 'anak', maksimal pilih 2 (satu Ayah, satu Ibu)
    if (selectedRole === 'anak') {
        if (input.checked) {
            const sameGenderExists = selectedRelIds.some(r => r.gender === gender);
            if (sameGenderExists) {
                showRelationError('relationError', 'Anda hanya bisa memilih satu ' + (gender === 'L' ? 'Ayah' : 'Ibu') + '!');
                input.checked = false;
                return;
            }
            if (selectedRelIds.length >= 2) {
                showRelationError('relationError', 'Maksimal hanya bisa memilih 2 orang tua!');
                input.checked = false;
                return;
            }
            if (!selectedRelIds.some(r => r.id == id)) {
                selectedRelIds.push({id, name, gender});
            }
        } else {
            selectedRelIds = selectedRelIds.filter(r => r.id != id);
        }
    } else {
        if (input.checked) {
            // Tambahkan jika belum ada
            if (!selectedRelIds.some(r => r.id == id)) {
                selectedRelIds.push({id, name, gender});
            }
        } else {
            // Hapus jika di-uncheck
            selectedRelIds = selectedRelIds.filter(r => r.id != id);
        }
    }
    
    updateSelectedTags();
}

function updateSelectedTags() {
    const container = document.getElementById('selectedMembers');
    container.innerHTML = '';
    
    selectedRelIds.forEach(rel => {
        const tag = document.createElement('div');
        tag.className = 'selected-tag';
        tag.innerHTML = `
            ${rel.name}
            <i class="bi bi-x-circle-fill remove-tag" onclick="removeRelation('${rel.id}')"></i>
        `;
        container.appendChild(tag);
    });
    
    if (selectedRelIds.length > 0) {
        selectedRelGender = selectedRelIds[0].gender; // Pakai gender orang pertama untuk acuan
        document.getElementById('btnNext3').disabled = false;
    } else {
        selectedRelGender = '';
        document.getElementById('btnNext3').disabled = true;
    }
    
    // Sinkronisasi dengan checkbox di list pencarian (jika terlihat)
    document.querySelectorAll('input[name="rel_id_search"]').forEach(input => {
        input.checked = selectedRelIds.some(r => r.id == input.value);
    });
}

function removeRelation(id) {
    selectedRelIds = selectedRelIds.filter(r => r.id != id);
    updateSelectedTags();
}

// Gender Lock Logic
function setupGenderLock() {
    const radioL = document.querySelector('input[name="gender"][value="L"]');
    const radioP = document.querySelector('input[name="gender"][value="P"]');
    const lblL = radioL.closest('.gender-radio');
    const lblP = radioP.closest('.gender-radio');
    
    // Reset
    radioL.disabled = false;
    radioP.disabled = false;
    lblL.classList.remove('disabled');
    lblP.classList.remove('disabled');
    radioL.checked = false;
    radioP.checked = false;
    
    if (selectedRole === 'pasangan') {
        if (selectedRelGender === 'L') {
            // Must be Perempuan
            radioP.checked = true;
            radioL.disabled = true;
            lblL.classList.add('disabled');
        } else if (selectedRelGender === 'P') {
            // Must be Laki-laki
            radioL.checked = true;
            radioP.disabled = true;
            lblP.classList.add('disabled');
        }
    }
    checkForm4();
}

function previewUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewPhoto').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function checkForm4() {
    const name = document.getElementById('fullName').value.trim();
    const dob = document.getElementById('birthDate').value;
    const gender = document.querySelector('input[name="gender"]:checked');
    
    const btn = document.getElementById('btnSubmit');
    if (name && dob && gender) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

function submitForm() {
    const name = document.getElementById('fullName').value.trim();
    const dob = document.getElementById('birthDate').value;
    const gender = document.querySelector('input[name="gender"]:checked').value;
    const btn = document.getElementById('btnSubmit');
    const errorMsg = document.getElementById('errorMsg');
    
    btn.disabled = true;
    btn.innerHTML = 'Menyimpan...';
    errorMsg.style.display = 'none';
    
    const formData = new FormData();
    formData.append('role', selectedRole);
    
    // Append rel_id array
    selectedRelIds.forEach(rel => {
        formData.append('rel_id[]', rel.id);
    });
    
    formData.append('full_name', name);
    formData.append('birth_date', dob);
    formData.append('gender', gender);
    
    const gen = document.getElementById('generasi');
    if (gen && gen.value) {
        formData.append('generasi', gen.value);
    }
    
    const photoInput = document.getElementById('photoInput');
    if (photoInput.files.length > 0) {
        formData.append('photo', photoInput.files[0]);
    }
    
    fetch(saveApiUrl, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            newMemberId = data.id || null;
            document.getElementById('successName').innerText = name.split(' ')[0]; // Panggilan
            
            const previewPhoto = document.getElementById('previewPhoto').src;
            document.getElementById('successPhoto').src = previewPhoto;

            document.getElementById('step3').style.display = 'none';
            document.getElementById('step5').style.display = 'block';
            loadMiniTree();
        } else {
            errorMsg.innerText = data.message;
            errorMsg.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = 'Selesai <i class="bi bi-check2"></i>';
        }
    })
    .catch(err => {
        errorMsg.innerText = 'Terjadi kesalahan server.';
        errorMsg.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = 'Selesai <i class="bi bi-check2"></i>';
    });
}

function loadMiniTree() {
    if (!newMemberId) return;

    fetch(detailApiUrl + '?id=' + newMemberId + '&preview=1')
    .then(res => res.json())
    .then(data => {
        // Anda
        const namaAnda = document.getElementById('successName').innerText || 'A';
        const inisialAnda = namaAnda.charAt(0).toUpperCase();
        
        // Backend already resolves 'foto' to a full URL or a placeholder
        const photoAnda = data.foto ? data.foto : `https://ui-avatars.com/api/?name=${inisialAnda}&background=CBD9CF&color=4A6055&size=100`;
        document.getElementById('miniPhotoAnda').src = photoAnda;

        // Orang Tua
        const ortuContainer = document.getElementById('cardsOrangTua');
        const nodeOrtu = document.getElementById('nodeOrangTua');
        const lineOrtuToAnda = document.getElementById('lineOrtuToAnda');
        ortuContainer.innerHTML = '';
        
        let hasOrtu = false;
        if (data.orang_tua && data.orang_tua.length > 0) {
            hasOrtu = true;
            data.orang_tua.forEach(ot => {
                const panggilan = ot.nama.split(' ')[0];
                const foto = ot.foto;
                ortuContainer.innerHTML += `<div class="mini-card"><img src="${foto}"><span>${panggilan}</span></div>`;
            });
        }
        
        if (hasOrtu) {
            nodeOrtu.style.display = 'flex';
            lineOrtuToAnda.style.display = 'block';
        }

        // Pasangan & Anak
        let hasBawah = false;
        const rowBawah = document.getElementById('rowBawah');
        const nodePasangan = document.getElementById('nodePasangan');
        const cardsPasangan = document.getElementById('cardsPasangan');
        const nodeAnak = document.getElementById('nodeAnak');
        const cardsAnak = document.getElementById('cardsAnak');
        
        cardsPasangan.innerHTML = '';
        cardsAnak.innerHTML = '';

        if (data.pasangan && data.pasangan.length > 0) {
            hasBawah = true;
            nodePasangan.style.display = 'flex';
            data.pasangan.forEach(p => {
                const panggilan = p.nama.split(' ')[0];
                const foto = p.foto;
                cardsPasangan.innerHTML += `<div class="mini-card"><img src="${foto}"><span>${panggilan}</span></div>`;
            });
        }

        if (data.anak_anak && data.anak_anak.length > 0) {
            hasBawah = true;
            nodeAnak.style.display = 'flex';
            data.anak_anak.forEach(a => {
                const panggilan = a.nama.split(' ')[0];
                const foto = a.foto;
                cardsAnak.innerHTML += `<div class="mini-card"><img src="${foto}"><span>${panggilan}</span></div>`;
            });
        }

        if (hasBawah) {
            rowBawah.style.display = 'flex';
            document.getElementById('lineAndaToBottom').style.display = 'block';
        }
    });
}

function goToFinalStep() {
    document.getElementById('step5').style.display = 'none';
    document.getElementById('step4').style.display = 'block';
}

function finishWizard() {
    if (typeof isSignupFlow !== 'undefined' && isSignupFlow) {
        window.location.href = baseTreeUrl.replace('/familytree', '/auth/trigger_otp');
    } else {
        window.location.href = baseTreeUrl;
    }
}
