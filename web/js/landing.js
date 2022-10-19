const openModalLinks = document.getElementsByClassName("open-modal");
const closeModalLinks = document.getElementsByClassName("form-modal-close");
const overlay = document.getElementsByClassName("overlay")[0];

for (let i = 0; i < openModalLinks.length; i++) {
  const modalLink = openModalLinks[i];

  modalLink.addEventListener("click", function (event) {
    const modalId = event.currentTarget.getAttribute("data-for");

    const modal = document.getElementById(modalId);
    modal.setAttribute("style", "display: block");
    overlay.setAttribute("style", "display: block");

  });
}

function closeModal(event) {
  const modal = event.currentTarget.parentElement;

  modal.removeAttribute("style");
  overlay.removeAttribute("style");
}

for (let j = 0; j < closeModalLinks.length; j++) {
  const closeModalLink = closeModalLinks[j];

  closeModalLink.addEventListener("click", closeModal)
}