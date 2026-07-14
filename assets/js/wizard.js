let currentStep = 0;
let selectedRole = '';
let selectedRelId = '';
let selectedRelGender = '';
let newMemberId = null;

// Step Navigation
function nextStep(stepIndex) {
    if (stepIndex === 1) {
        document.getElementById('wizardHeader').style.display = 'flex';
    }
    
    // Update Header Titles based on step
    if (stepIndex === 1) {
        document.getElementById('stepTitle').innerText = 'Siapa Kamu?';
    } else if (stepIndex === 2) {
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
    } else if (stepIndex === 3) {
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
        for (let i = 1; i <= 3; i++) {
            const leaf = document.getElementById('leaf' + i);
            if (i <= stepIndex) leaf.classList.add('active');
            else leaf.classList.remove('active');
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
    }
}

// Search Logic
let searchTimeout;
function searchMember(term) {
    clearTimeout(searchTimeout);
    const listEl = document.getElementById('memberList');
    
    if (term.length < 2) {
        listEl.innerHTML = '<div style="text-align:center; color:gray; font-size:12px; padding:10px;">Ketik minimal 2 huruf</div>';
        document.getElementById('btnNext2').disabled = true;
        return;
    }
    
    listEl.innerHTML = '<div style="text-align:center; color:gray; font-size:12px; padding:10px;">Mencari...</div>';
    
    searchTimeout = setTimeout(() => {
        fetch(searchApiUrl + '?term=' + encodeURIComponent(term))
            .then(res => res.json())
            .then(data => {
                listEl.innerHTML = '';
                if (data.length === 0) {
                    listEl.innerHTML = '<div style="text-align:center; color:gray; font-size:12px; padding:10px;">Tidak ditemukan.</div>';
                    return;
                }
                
                data.forEach(item => {
                    const html = `
                        <label class="member-item">
                            <img src="https://placehold.co/40x40/CBD9CF/4A6055?text=${item.full_name.charAt(0)}" alt="">
                            <div style="flex:1">
                                <strong style="font-size:14px; display:block; color:#4A6055;">${item.full_name}</strong>
                                <span style="font-size:11px; color:#5a5c50;">${item.gender === 'L' ? 'Laki-laki' : 'Perempuan'}</span>
                            </div>
                            <input type="radio" name="rel_id" value="${item.id}" data-gender="${item.gender}" onchange="selectRelation(this)">
                        </label>
                    `;
                    listEl.insertAdjacentHTML('beforeend', html);
                });
            })
            .catch(err => {
                listEl.innerHTML = '<div style="text-align:center; color:red; font-size:12px; padding:10px;">Error pencarian.</div>';
            });
    }, 500);
}

function selectRelation(radio) {
    selectedRelId = radio.value;
    selectedRelGender = radio.getAttribute('data-gender');
    document.getElementById('btnNext2').disabled = false;
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
    checkForm3();
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

function checkForm3() {
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
    formData.append('rel_id', selectedRelId);
    formData.append('full_name', name);
    formData.append('birth_date', dob);
    formData.append('gender', gender);
    
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
            document.getElementById('step4').style.display = 'block';
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
    document.getElementById('step4').style.display = 'none';
    document.getElementById('step5').style.display = 'block';

    if (!newMemberId) return;

    fetch(detailApiUrl + '?id=' + newMemberId)
    .then(res => res.json())
    .then(data => {
        // Anda
        const namaAnda = document.getElementById('successName').innerText || 'A';
        const inisialAnda = namaAnda.charAt(0).toUpperCase();
        const photoAnda = data.photo ? `${baseTreeUrl.replace('/familytree', '')}/assets/uploads/${data.photo}` : `https://placehold.co/70x70/CBD9CF/4A6055?text=${inisialAnda}`;
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

function finishWizard() {
    window.location.href = baseTreeUrl;
}
