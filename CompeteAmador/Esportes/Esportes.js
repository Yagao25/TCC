 // Função para carregar cidades com base no estado selecionado
document.getElementById('opcao_estado').addEventListener('change', function() {
    var estadoId = this.value;
    var cidadeSelect = document.getElementById('opcao_cidade');
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
    cidadeSelect.disabled = true;
    if (estadoId !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cidade.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                cidadeSelect.innerHTML = xhr.responseText;
                cidadeSelect.disabled = false;
            } else {
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            }
        };
        xhr.send('uf=' + estadoId);
    } else {
        cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const dataInput = document.getElementById('datainicio');
    
    // Obtém a data atual no formato 'yyyy-mm-dd' para compatibilidade com o atributo 'min' de <input type="date">'
    const hoje = new Date();
    const dia = hoje.getDate().toString().padStart(2, '0'); // Dia com zero à esquerda se necessário
    const mes = (hoje.getMonth() + 1).toString().padStart(2, '0'); // Mês com zero à esquerda se necessário (janeiro é 0)
    const ano = hoje.getFullYear();
    const dataFormatada = `${ano}-${mes}-${dia}`;

    dataInput.setAttribute('min', dataFormatada);  // Define o atributo 'min' para evitar a seleção de datas passadas
});


document.addEventListener('DOMContentLoaded', function() {
    const generoSelect = document.getElementById('genero');
    const categoriaSelect = document.getElementById('categoria');
    const estadoSelect = document.getElementById('opcao_estado');
    const cidadeSelect = document.getElementById('opcao_cidade');
    const dataInput = document.getElementById('datainicio');

    const cards = document.querySelectorAll('.card');

    generoSelect.addEventListener('change', filtrarCards);
    categoriaSelect.addEventListener('change', filtrarCards);
    estadoSelect.addEventListener('change', filtrarCards);
    cidadeSelect.addEventListener('change', filtrarCards);
    dataInput.addEventListener('change', filtrarCards);

    function filtrarCards() {
        const generoSelecionado = generoSelect.value.toLowerCase();
        const categoriaSelecionada = categoriaSelect.value.toLowerCase();
        const estadoSelecionado = estadoSelect.value.toLowerCase();
        const cidadeSelecionado = cidadeSelect.value.toLowerCase();
        const dataSelecionada = dataInput.value;

        let count = 0;

        cards.forEach(card => {
            const cardGenero = card.getAttribute('data-genero').toLowerCase();
            const cardCategoria = card.getAttribute('data-categoria').toLowerCase();
            const cardEstado = card.getAttribute('data-estado').toLowerCase();
            const cardCidade = card.getAttribute('data-cidade').toLowerCase();
            const cardDataInicio = card.getAttribute('data-inicio');

            let dataValida = true;

            if (dataSelecionada !== '') {
                const [dia, mes, ano] = cardDataInicio.split('/');
                const cardDate = new Date(`${ano}-${mes}-${dia}`);
                const selectedDate = new Date(dataSelecionada);

                dataValida = selectedDate.getTime() === cardDate.getTime();
            }

            const atendeFiltro =
                (estadoSelecionado === '' || estadoSelecionado === cardEstado) &&
                (generoSelecionado === '' || generoSelecionado === cardGenero) &&
                (cidadeSelecionado === '' || cidadeSelecionado === cardCidade) &&
                (categoriaSelecionada === '' || categoriaSelecionada === cardCategoria) &&
                dataValida;

            if (atendeFiltro) {
                card.style.display = 'block';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        verificarCards(count);
    }

    function verificarCards(count = null, inicial = false) {
        if (count === null) {
            count = 0;
            cards.forEach(card => {
                if (card.style.display !== 'none') {
                    count++;
                }
            });
        }

        const mensagemExistente = document.querySelector('.alert');
        if (mensagemExistente) {
            mensagemExistente.remove();
        }

        if (count === 0) {
            const mensagem = document.createElement('p');
            if (inicial) {
                mensagem.textContent = 'Nenhuma competição disponível no momento.';
                mensagem.classList.add('alert', 'alert-warning', 'mt-4');
            } else {
                mensagem.textContent = 'Não há competições cadastradas que atendam aos critérios escolhidos no filtro.';
                mensagem.classList.add('alert', 'alert-info', 'mt-4');
            }
            document.querySelector('.container').appendChild(mensagem);
        }
    }

    function ocultarCardsPassados() {
        const dataAtual = new Date();

        cards.forEach(card => {
            const dataInicio = card.getAttribute('data-inicio');
            const [dia, mes, ano] = dataInicio.split('/');
            const dataInicioFormatada = new Date(ano, mes - 1, dia);

            if (dataInicioFormatada < dataAtual) {
                card.style.display = 'none';
            }
        });
    }

    function mostrarMensagemInicial() {
        verificarCards(null, true);
    }

    window.onload = function() {
        ocultarCardsPassados();
        mostrarMensagemInicial();
    };
});
