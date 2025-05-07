registerForm.addEventListener('submit', (event) => {
    event.preventDefault(); 
  
    const fullname = document.getElementById('fullname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
  
    if (!fullname || !email || !password || !confirmPassword) {
      messageDiv.textContent = 'Todos os campos são obrigatórios.';
      messageDiv.classList.remove('success-message');
      return;
    }
  
    if (password !== confirmPassword) {
      messageDiv.textContent = 'As senhas não coincidem.';
      messageDiv.classList.remove('success-message');
      return;
    }
  
    messageDiv.textContent = 'Cadastro realizado com sucesso!';
    messageDiv.classList.add('success-message');
  
    setTimeout(() => {
        window.location.href = 'Book_Club.html';
        }, 2000);
  });