const mensagem = document.getElementById("mensagemSucesso");
if (mensagem) {
  setTimeout(() => {
    mensagem.classList.remove("show");
    setTimeout(() => {
      mensagem.remove();
    }, 150);
  }, 4000);
}
