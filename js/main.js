AOS.init({
  offset: 100,
});

const header = document.getElementById('header');
const spacer = document.getElementById('header-spacer');

spacer.style.height = `${header.offsetHeight}px`;
