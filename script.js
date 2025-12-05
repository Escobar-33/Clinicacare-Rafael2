const form = document.getElementById('formAgendamento');
const lista = document.getElementById('listaConsultas');

// Carrega consultas ao abrir
document.addEventListener("DOMContentLoaded", carregarConsultas);

form.addEventListener("submit", function (event) {
    event.preventDefault();

    const nome = document.getElementById("nome").value.trim();
    const medico = document.getElementById("medico").value;
    const data = document.getElementById("data").value;
    const hora = document.getElementById("hora").value;

    let consultas = JSON.parse(localStorage.getItem("consultas")) || [];

    // -----------------------------
    // 🔒 REGRA 1 – Não agendar no passado
    // -----------------------------
    const hoje = new Date().toISOString().split("T")[0];
    if (data < hoje) {
        alert("❌ Não é possível agendar em datas passadas.");
        return;
    }

    // -----------------------------
    // 🔒 REGRA 2 – Horário permitido apenas entre 08:00 e 18:00
    // -----------------------------
    if (hora < "08:00" || hora > "18:00") {
        alert("❌ O horário deve ser entre 08:00 e 18:00.");
        return;
    }

    // -----------------------------
    // 🔒 REGRA 3 – Não permitir conflito de horário para o mesmo médico
    // -----------------------------
    const conflito = consultas.some(c =>
        c.medico === medico &&
        c.data === data &&
        c.hora === hora
    );

    if (conflito) {
        alert("❌ Este médico já possui consulta neste horário.");
        return;
    }

    // -----------------------------
    // 🔒 REGRA 4 – Paciente só pode 1 consulta por dia
    // -----------------------------
    const jaTemConsulta = consultas.some(c =>
        c.nome.toLowerCase() === nome.toLowerCase() &&
        c.data === data
    );

    if (jaTemConsulta) {
        alert("❌ O paciente já possui uma consulta marcada nesse dia.");
        return;
    }

    // -----------------------------
    // 🔒 REGRA 5 – Nome mínimo de 3 caracteres
    // -----------------------------
    if (nome.length < 3) {
        alert("❌ O nome deve ter ao menos 3 caracteres.");
        return;
    }

    // Se todas regras forem atendidas → salva
    const consulta = { nome, medico, data, hora };
    consultas.push(consulta);
    localStorage.setItem("consultas", JSON.stringify(consultas));

    alert("✅ Consulta marcada com sucesso!");
    form.reset();
    carregarConsultas();
});

function carregarConsultas() {
    lista.innerHTML = "";
    const consultas = JSON.parse(localStorage.getItem("consultas")) || [];

    consultas.forEach((c) => {
        const li = document.createElement("li");
        li.textContent = `${c.nome} - ${c.medico} - ${c.data} às ${c.hora}`;
        lista.appendChild(li);
    });
}
