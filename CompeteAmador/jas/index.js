// Função para alternar visibilidade da senha
function togglePasswordVisibilityl() {
    const senhaInput = document.getElementById('senhal');
        const eyeIcon = document.getElementById('eye-icon');
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
}
// Função para alternar visibilidade da senha
function togglePasswordVisibility() {
    const senhaInput = document.getElementById('senha');
        const eyeIcon = document.getElementById('eye-icon');
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
}

// Função para alternar visibilidade da senha de confirmação
function togglePasswordVisibilityc() {
    const senhaInput = document.getElementById('senhac');
        const eyeIcon = document.getElementById('eye-icon2');
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
}

// Função para formatar número de telefone
function formatPhoneNumber(input) {
    const phoneNumber = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    let formattedPhoneNumber = '';
    const part1 = phoneNumber.slice(0, 2);
    const part2 = phoneNumber.slice(2, 7);
    const part3 = phoneNumber.slice(7);

    if (part1) {
        formattedPhoneNumber += `(${part1}`;
    }
    if (part2) {
        formattedPhoneNumber += `) ${part2}`;
    }
    if (part3) {
        formattedPhoneNumber += `-${part3}`;
    }

    input.value = formattedPhoneNumber;
}
// Função para formatar a data no formato dd/mm/yyyy
function formatDate(input) {
    let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    // Adiciona o separador '/' a cada dois dígitos
    if (value.length <= 2) {
        value = value.replace(/(\d{2})/, '$1'); // Formata o dia
    } else if (value.length <= 4) {
        value = value.replace(/(\d{2})(\d{1})/, '$1/$2'); // Formata o mês ao digitar
    } else {
        value = value.replace(/(\d{2})(\d{2})(\d{1})/, '$1/$2/$3'); // Formata o ano e coloca '/'
    }

    input.value = value;
}
// Função para formatar CPF
function formatCPF(campo) {
    let cpf = campo.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    if (cpf.length <= 3) {
        campo.value = cpf;
    } else if (cpf.length <= 6) {
        campo.value = cpf.replace(/(\d{3})(\d{0,3})/, '$1.$2');
    } else if (cpf.length <= 9) {
        campo.value = cpf.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
    } else {
        campo.value = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
    }
}