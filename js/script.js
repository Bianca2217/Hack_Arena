 // Menu mobile
  const hamb = document.querySelector('.hamb');
  const menu = document.getElementById('menu');
  hamb.addEventListener('click', () => {
    const open = hamb.getAttribute('aria-expanded') === 'true';
    hamb.setAttribute('aria-expanded', String(!open));
    menu.classList.toggle('open');
    document.body.classList.toggle('no-scroll');
  });
  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', e=>{
      const id = a.getAttribute('href');
      if(id.length>1){
        e.preventDefault();
        document.querySelector(id).scrollIntoView({behavior:'smooth'});
        menu.classList.remove('open');
        hamb.setAttribute('aria-expanded','false');
        document.body.classList.remove('no-scroll');
      }
    });
  });
  document.getElementById('year').textContent = new Date().getFullYear();
