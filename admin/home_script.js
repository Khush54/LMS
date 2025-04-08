document.querySelectorAll('[data-bs-theme-value]').forEach(button => {
  button.addEventListener('click', () => {
    const theme = button.getAttribute('data-bs-theme-value');
    document.documentElement.setAttribute('data-bs-theme', theme);
    const iframe = document.getElementById('content-frame');
    if (iframe?.contentDocument?.documentElement) {
      iframe.contentDocument.documentElement.setAttribute('data-bs-theme', theme);
    }
  });
});

function updateTooltips() {
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(el => {
      const tooltip = bootstrap.Tooltip.getInstance(el);
      if (window.innerWidth < 768) {
        if (!tooltip) {
          new bootstrap.Tooltip(el);
        }
      } else {
        if (tooltip) {
          tooltip.dispose();
        }
      }
    });
}

window.addEventListener('load', updateTooltips);
window.addEventListener('resize', updateTooltips);


function loadPage(page) {
  const frame = document.getElementById('content-frame');
  frame.src = page;

  frame.onload = () => {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    if (frame?.contentDocument?.documentElement) {
      frame.contentDocument.documentElement.setAttribute('data-bs-theme', currentTheme);
    }
  };
}
