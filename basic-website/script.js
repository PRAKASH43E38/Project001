const yearElement = document.getElementById('year');
const themeToggle = document.getElementById('themeToggle');
const editToggle = document.getElementById('editToggle');
const contactForm = document.getElementById('contactForm');
const formFeedback = document.getElementById('formFeedback');
const editableElements = document.querySelectorAll('.editable[data-edit-key]');

yearElement.textContent = new Date().getFullYear();

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
  document.body.classList.add('dark');
  themeToggle.textContent = '☀️';
}

const applyEditModeState = (isEnabled) => {
  document.body.classList.toggle('edit-mode', isEnabled);
  editToggle.classList.toggle('active', isEnabled);
  editToggle.textContent = isEnabled ? 'Disable Edit Mode' : 'Enable Edit Mode';
  editToggle.setAttribute('aria-pressed', String(isEnabled));

  editableElements.forEach((element) => {
    element.setAttribute('contenteditable', String(isEnabled));
  });
};

const savedEditMode = localStorage.getItem('editMode') === 'on';
applyEditModeState(savedEditMode);

editableElements.forEach((element) => {
  const key = element.dataset.editKey;
  const savedContent = localStorage.getItem(`content:${key}`);

  if (savedContent) {
    element.textContent = savedContent;
  }

  element.addEventListener('blur', () => {
    localStorage.setItem(`content:${key}`, element.textContent.trim());
  });
});

themeToggle.addEventListener('click', () => {
  document.body.classList.toggle('dark');
  const isDark = document.body.classList.contains('dark');
  themeToggle.textContent = isDark ? '☀️' : '🌙';
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
});

editToggle.addEventListener('click', () => {
  const nextState = !document.body.classList.contains('edit-mode');
  applyEditModeState(nextState);
  localStorage.setItem('editMode', nextState ? 'on' : 'off');
});

contactForm.addEventListener('submit', (event) => {
  event.preventDefault();

  if (!contactForm.checkValidity()) {
    formFeedback.textContent = 'Please fill out all fields with valid information.';
    return;
  }

  const name = document.getElementById('name').value.trim();
  formFeedback.textContent = `Thanks, ${name}! Your message has been recorded.`;
  contactForm.reset();
});
