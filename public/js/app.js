/* SIAKAD SMK NU - Enhanced JavaScript */
document.addEventListener('DOMContentLoaded',function(){
/* SIDEBAR TOGGLE */
var sb=document.querySelector('.sidebar');
if(sb){
var tgl=document.createElement('button');tgl.className='sidebar-toggle';tgl.setAttribute('aria-label','Toggle');
tgl.innerHTML='<span></span><span></span><span></span>';document.body.appendChild(tgl);
var ov=document.createElement('div');ov.className='sidebar-overlay';document.body.appendChild(ov);
function openS(){sb.classList.add('open');tgl.classList.add('active');ov.classList.add('show');document.body.style.overflow='hidden'}
function closeS(){sb.classList.remove('open');tgl.classList.remove('active');ov.classList.remove('show');document.body.style.overflow=''}
tgl.addEventListener('click',function(){sb.classList.contains('open')?closeS():openS()});
ov.addEventListener('click',closeS);
sb.querySelectorAll('.nav-item').forEach(function(n){n.addEventListener('click',function(){if(window.innerWidth<=1024)closeS()})});
window.addEventListener('resize',function(){if(window.innerWidth>1024)closeS()});
}
/* DROPDOWN */
window.toggleDropdown=function(){var d=document.getElementById('profileDropdown');if(d){d.classList.toggle('show');d.querySelectorAll('a').forEach(function(a,i){a.style.animation=d.classList.contains('show')?'fadeInUp .2s ease '+i*.05+'s both':'none'})}};
document.addEventListener('click',function(e){if(!e.target.closest('.profile-dropdown')&&!e.target.closest('.profile-avatar-sm')){var d=document.getElementById('profileDropdown');if(d)d.classList.remove('show')}});
/* SELECT ALL */
var sa=document.getElementById('selectAll');
if(sa){sa.addEventListener('change',function(){document.querySelectorAll('.siswa-check:not(:disabled)').forEach(function(b){b.checked=sa.checked});uc()});document.querySelectorAll('.siswa-check').forEach(function(b){b.addEventListener('change',uc)})}
function uc(){var c=document.querySelectorAll('.siswa-check:checked').length;var el=document.getElementById('selectedCount');if(el){el.textContent=c+' siswa dipilih';el.style.color=c>0?'#00923F':'#ADB5BD';el.style.fontWeight=c>0?'600':'400'}}
/* GURU CHECK */
var gs=document.getElementById('guruSelect');
if(gs){var fm=gs.closest('form'),hE=fm?fm.querySelector('[name="hari"]'):null,mE=fm?fm.querySelector('[name="jam_mulai"]'):null,sE=fm?fm.querySelector('[name="jam_selesai"]'):null,stE=document.getElementById('guruStatus');
function chk(){if(!gs.value||!hE.value||!mE.value||!sE.value)return;if(stE)stE.innerHTML='<span style="color:#ADB5BD">Mengecek...</span>';
fetch(window.location.pathname+'?page=jadwal&action=checkGuru&guru_id='+gs.value+'&hari='+hE.value+'&jam_mulai='+mE.value+'&jam_selesai='+sE.value).then(function(r){return r.json()}).then(function(d){if(stE){stE.style.animation='fadeIn .3s';stE.innerHTML=d.available?'<span style="color:#00923F;font-weight:600">&#10003; Guru tersedia</span>':'<span style="color:#DC3545;font-weight:600">&#10007; Guru tidak tersedia pada waktu tersebut</span>'}}).catch(function(){})}
[gs,hE,mE,sE].forEach(function(el){if(el)el.addEventListener('change',chk)})}
/* MODAL */
window.showModal=function(t){var m=document.getElementById('successModal'),x=document.getElementById('modalText');if(m&&x){x.textContent=t;m.style.display='flex'}};
window.closeModal=function(){var m=document.getElementById('successModal');if(m){m.querySelector('.modal-box').style.animation='scaleIn .2s reverse';setTimeout(function(){m.style.display='none';m.querySelector('.modal-box').style.animation=''},200)}};
window.confirmDelete=function(u,t){var m=document.getElementById('confirmModal'),x=document.getElementById('confirmText'),a=document.getElementById('confirmAction');if(m&&x&&a){x.textContent=t||'Yakin ingin menghapus data ini?';a.href=u;a.textContent='Ya, Hapus';m.style.display='flex'}};
window.confirmLogout=function(u){var m=document.getElementById('confirmModal'),x=document.getElementById('confirmText'),a=document.getElementById('confirmAction');if(m&&x&&a){x.textContent='Anda yakin ingin keluar dari sistem?';a.href=u;a.textContent='Ya, Keluar';m.style.display='flex'}};
window.closeConfirm=function(){var m=document.getElementById('confirmModal');if(m){m.querySelector('.modal-box').style.animation='scaleIn .2s reverse';setTimeout(function(){m.style.display='none';m.querySelector('.modal-box').style.animation=''},200)}};
/* AUTO-HIDE ALERTS */
document.querySelectorAll('.alert.success').forEach(function(el){setTimeout(function(){el.style.transition='all .5s ease';el.style.opacity='0';el.style.transform='translateY(-10px)';setTimeout(function(){el.remove()},500)},4000)});
/* TABLE ROW STAGGER */
document.querySelectorAll('.table tbody tr').forEach(function(r,i){r.style.animation='fadeIn .3s ease '+(i*.03)+'s both'});
/* NUMBER COUNTING ANIMATION */
document.querySelectorAll('.stat-info h3').forEach(function(el){var t=parseInt(el.textContent);if(isNaN(t)||t===0)return;var c=0,s=Math.ceil(t/25);el.textContent='0';var tm=setInterval(function(){c+=s;if(c>=t){c=t;clearInterval(tm)}el.textContent=c},30)});
/* RIPPLE EFFECT ON BUTTONS */
document.querySelectorAll('.btn').forEach(function(b){b.addEventListener('click',function(e){var r=document.createElement('span');r.style.cssText='position:absolute;border-radius:50%;background:rgba(255,255,255,.4);width:10px;height:10px;animation:ripple .6s linear;pointer-events:none;';var rc=this.getBoundingClientRect();r.style.left=(e.clientX-rc.left-5)+'px';r.style.top=(e.clientY-rc.top-5)+'px';this.appendChild(r);setTimeout(function(){r.remove()},600)})});
/* HEADER SCROLL SHADOW */
var th=document.querySelector('.top-header');
if(th)window.addEventListener('scroll',function(){th.style.boxShadow=window.scrollY>60?'0 2px 12px rgba(0,0,0,.08)':'0 1px 4px rgba(0,0,0,.03)'},{passive:true});
/* ESC KEY TO CLOSE */
document.addEventListener('keydown',function(e){if(e.key==='Escape'){document.querySelectorAll('.modal-overlay').forEach(function(m){if(m.style.display!=='none')m.style.display='none'});var dd=document.getElementById('profileDropdown');if(dd)dd.classList.remove('show')}});
/* CONSOLE BRANDING */
console.log('%c SIAKAD SMK NU ','background:linear-gradient(135deg,#00923F,#007532);color:white;font-size:14px;padding:6px 12px;border-radius:6px;font-weight:bold');

/* ===== SCROLL REVEAL for cards & rows ===== */
if('IntersectionObserver' in window){
  var io=new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){
        e.target.style.opacity='1';
        e.target.style.transform='translateY(0)';
        io.unobserve(e.target);
      }
    });
  },{threshold:0.08,rootMargin:'0px 0px -40px 0px'});
  document.querySelectorAll('.card, .stat-card, .mobile-card').forEach(function(el,i){
    if(el.style.animation) return; /* skip if already animated via CSS */
    el.style.opacity='0';
    el.style.transform='translateY(20px)';
    el.style.transition='opacity .5s cubic-bezier(0.4,0,0.2,1) '+(i*0.04)+'s, transform .5s cubic-bezier(0.4,0,0.2,1) '+(i*0.04)+'s';
    io.observe(el);
  });
}

/* ===== HEADER SHRINK ON SCROLL ===== */
var lastScroll=0, hdr=document.querySelector('.top-header');
if(hdr){
  window.addEventListener('scroll',function(){
    var cy=window.scrollY;
    if(cy>20){ hdr.classList.add('scrolled'); } else { hdr.classList.remove('scrolled'); }
    lastScroll=cy;
  },{passive:true});
}

/* ===== NAV ITEM CLICK FEEDBACK ===== */
document.querySelectorAll('.nav-item, .mobile-navbar a').forEach(function(link){
  link.addEventListener('click',function(e){
    if(link.classList.contains('active')) return;
    /* subtle pulse before navigation */
    link.style.transition='transform .1s ease';
    link.style.transform='scale(0.96)';
    setTimeout(function(){ link.style.transform=''; },100);
    /* progress bar */
    var bar=document.createElement('div');
    bar.className='page-loading';
    document.body.appendChild(bar);
  });
});

/* ===== SMOOTH FOCUS RING on form controls ===== */
document.querySelectorAll('.form-control').forEach(function(f){
  f.addEventListener('focus',function(){ f.parentElement.classList.add('is-focused'); });
  f.addEventListener('blur',function(){ f.parentElement.classList.remove('is-focused'); });
});

/* ===== ANIMATED PROFILE DROPDOWN ARROW ===== */
var pa=document.querySelector('.profile-avatar');
if(pa){
  pa.addEventListener('click',function(){
    var d=document.getElementById('profileDropdown');
    if(d && d.classList.contains('show')){
      pa.style.transform='scale(0.95)';
      setTimeout(function(){ pa.style.transform=''; },150);
    }
  });
}

/* ===== SEARCH BAR: focus animation ===== */
document.querySelectorAll('.search-bar-figma input').forEach(function(inp){
  inp.addEventListener('focus',function(){
    var icon=inp.parentElement.querySelector('.search-icon');
    if(icon) icon.style.transform='rotate(-8deg) scale(1.1)';
  });
  inp.addEventListener('blur',function(){
    var icon=inp.parentElement.querySelector('.search-icon');
    if(icon) icon.style.transform='';
  });
});

/* ===== TOAST HELPER (window.toast('msg','success')) ===== */
window.toast=function(msg,type){
  var t=document.createElement('div');
  t.className='toast toast-'+(type||'success');
  t.textContent=msg;
  document.body.appendChild(t);
  requestAnimationFrame(function(){ t.classList.add('show'); });
  setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); },400); },3500);
};

/* ===== SIDEBAR COLLAPSE/EXPAND (desktop) ===== */
window.toggleSidebarCollapse = function(){
  var sb=document.querySelector('.sidebar');
  var mc=document.querySelector('.main-content');
  if(!sb) return;
  var collapsed=sb.classList.toggle('collapsed');
  if(mc) mc.classList.toggle('sidebar-collapsed',collapsed);
  try{ localStorage.setItem('siakad_sb_collapsed',collapsed?'1':'0'); }catch(e){}
};
/* Restore collapse state on load (desktop only) */
(function(){
  try{
    if(window.innerWidth>1024 && localStorage.getItem('siakad_sb_collapsed')==='1'){
      var sb=document.querySelector('.sidebar');
      var mc=document.querySelector('.main-content');
      if(sb){ sb.classList.add('collapsed'); }
      if(mc){ mc.classList.add('sidebar-collapsed'); }
    }
  }catch(e){}
})();
/* ===== TABLE FILTER HELPER ===== */
window.filterTableRows = function(tableId, query){
  var tbl = document.getElementById(tableId);
  if(!tbl) return;
  var q = (query||'').toLowerCase().trim();
  tbl.querySelectorAll('tbody tr').forEach(function(row){
    var txt = row.textContent.toLowerCase();
    row.style.display = (!q || txt.indexOf(q)>-1) ? '' : 'none';
  });
};

/* Keyboard shortcut: Ctrl+B toggle sidebar */
document.addEventListener('keydown',function(e){
  if((e.ctrlKey||e.metaKey) && e.key==='b'){
    e.preventDefault();
    if(window.innerWidth>1024) window.toggleSidebarCollapse();
  }
});
/* On resize: if switching to mobile, clear collapsed state visually */
window.addEventListener('resize',function(){
  var sb=document.querySelector('.sidebar');
  var mc=document.querySelector('.main-content');
  if(window.innerWidth<=1024){
    if(mc) mc.classList.remove('sidebar-collapsed');
  } else {
    try{
      if(localStorage.getItem('siakad_sb_collapsed')==='1'){
        if(sb) sb.classList.add('collapsed');
        if(mc) mc.classList.add('sidebar-collapsed');
      }
    }catch(e){}
  }
});

});
