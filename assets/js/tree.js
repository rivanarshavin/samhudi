const treeContainer = document.getElementById('tree');
console.log("Tree.js sudah dimuat!");

fetch('/samhudi/samhudi/index.php/familytree/get_family_tree')
  .then(res => res.json())
  .then(data => {
    treeContainer.innerHTML = '';
    if (data.error) {
      treeContainer.innerHTML = `<div class="error-msg">${escapeHtml(data.error)}</div>`;
      return;
    }
    treeContainer.appendChild(buildBranch(data));
  })
  .catch(err => {
    treeContainer.innerHTML = `<div class="error-msg">Gagal memuat data keluarga. Periksa koneksi database / api/get_tree.php.</div>`;
    console.error(err);
  });

function escapeHtml(str){
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

function firstName(full){
  return (full || '').replace(/^(H\.|Hj\.)\s*/, '').split(' ')[0];
}

function makePolaroid(person, rotClass){
  const div = document.createElement('div');
  div.className = 'polaroid ' + rotClass;
  div.tabIndex = 0;
  div.setAttribute('role', 'button');
  div.setAttribute('aria-label', 'Lihat data ' + person.nama);
  div.innerHTML = `<img src="${person.foto}" alt="Foto ${escapeHtml(person.nama)}" loading="lazy">
                    <div class="cap">${escapeHtml(firstName(person.nama))}</div>`;
  const open = () => openPanel(person, div);
  div.addEventListener('click', open);
  div.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } });
  return div;
}

function makeToggle(person){
  const toggle = document.createElement('button');
  toggle.className = 'toggle';
  toggle.setAttribute('aria-label', 'Buka cabang ' + person.nama);
  toggle.setAttribute('aria-expanded', 'false');
  const barH = document.createElement('span'); barH.className = 'bar-h';
  const barV = document.createElement('span'); barV.className = 'bar-v';
  toggle.appendChild(barH);
  toggle.appendChild(barV);
  return toggle;
}

function buildBranch(person){
  const branch = document.createElement('div');
  branch.className = 'branch';

  const unit = document.createElement('div');
  unit.className = 'node-unit';

  const hasChildren = Array.isArray(person.children) && person.children.length > 0;
  let toggle = null;
  if (hasChildren) {
    toggle = makeToggle(person);
    unit.appendChild(toggle);
  }

  const coupleWrap = document.createElement('div');
  coupleWrap.className = 'couple';
  coupleWrap.appendChild(makePolaroid(person, 'r1'));
  if (person.pasangan) coupleWrap.appendChild(makePolaroid(person.pasangan, 'r2'));
  unit.appendChild(coupleWrap);

  branch.appendChild(unit);

  if (hasChildren) {
    const childrenRow = document.createElement('div');
    childrenRow.className = 'children-row';

    const kids = document.createElement('div');
    kids.className = 'kids' + (person.children.length === 1 ? ' single' : '');

    person.children.forEach((child, i) => {
      const cb = document.createElement('div');
      cb.className = 'child-branch';
      cb.style.setProperty('--i', i);
      cb.appendChild(buildBranch(child));
      kids.appendChild(cb);
    });

    childrenRow.appendChild(kids);
    branch.appendChild(childrenRow);

    let expanded = false;
    toggle.addEventListener('click', () => {
      expanded = !expanded;
      toggle.classList.toggle('open', expanded);
      toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      toggle.setAttribute('aria-label', (expanded ? 'Tutup cabang ' : 'Buka cabang ') + person.nama);
      childrenRow.classList.toggle('open', expanded);
    });
  }

  return branch;
}

/* ---------------------------------------------------------
   POPUP (muncul dekat foto yang diklik)
--------------------------------------------------------- */
const popup = document.getElementById('infoPopup');
const ipName = document.getElementById('ipName');
const ipLines = document.getElementById('ipLines');
const popupClose = document.getElementById('popupClose');
let lastFocused = null;
let activeAnchor = null;

const popupFieldOrder = [
  { key: 'tanggal_lahir', label: 'Tanggal Lahir' },
  { key: 'tempat_tinggal', label: 'Tempat Tinggal' },
  { key: 'status', label: 'Status' },
];

function openPanel(person, anchorEl){
  lastFocused = document.activeElement;
  activeAnchor = anchorEl;

  ipName.textContent = person.nama;
  ipLines.innerHTML = '';
  popupFieldOrder.forEach(f => {
    if (!person[f.key]) return;
    const row = document.createElement('div');
    row.className = 'ip-line';
    row.innerHTML = `<span class="ip-label">${f.label}</span><span class="ip-value">${escapeHtml(String(person[f.key]))}</span>`;
    ipLines.appendChild(row);
  });

  popup.classList.add('open');
  popup.setAttribute('aria-hidden', 'false');
  positionPopup(anchorEl);
  popupClose.focus();
}

function positionPopup(anchorEl){
  const rect = anchorEl.getBoundingClientRect();
  const pw = popup.offsetWidth;
  const ph = popup.offsetHeight;
  const margin = 10;

  let left = rect.left + rect.width / 2 - pw / 2;
  left = Math.max(margin, Math.min(left, window.innerWidth - pw - margin));

  let top = rect.bottom + margin;
  let arrowTop = true;
  if (top + ph > window.innerHeight - margin) {
    top = rect.top - ph - margin;
    arrowTop = false;
    if (top < margin) top = margin; // fallback kalau layar sangat pendek
  }

  popup.style.left = left + 'px';
  popup.style.top = top + 'px';
  popup.classList.toggle('arrow-up', arrowTop);
  popup.classList.toggle('arrow-down', !arrowTop);
  popup.style.setProperty('--arrow-x', (rect.left + rect.width / 2 - left) + 'px');
}

function closePanel(){
  popup.classList.remove('open');
  popup.setAttribute('aria-hidden', 'true');
  if (lastFocused) lastFocused.focus();
  activeAnchor = null;
}

popupClose.addEventListener('click', closePanel);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });
document.addEventListener('click', e => {
  if (!popup.classList.contains('open')) return;
  if (popup.contains(e.target) || (activeAnchor && activeAnchor.contains(e.target))) return;
  closePanel();
});
window.addEventListener('resize', () => { if (activeAnchor && popup.classList.contains('open')) positionPopup(activeAnchor); });
window.addEventListener('scroll', () => { if (activeAnchor && popup.classList.contains('open')) positionPopup(activeAnchor); }, true);