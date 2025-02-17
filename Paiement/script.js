document.addEventListener("DOMContentLoaded", function() {
    var paymentMethod = document.getElementById("paymentMethod");
    var cardDetails = document.querySelector(".card-details");
    var showCardDetailsButton = document.getElementById("showCardDetails");
    var cardDetailsModal = document.getElementById("cardDetailsModal");
    var closeButton = document.querySelector(".close");
    var submitCardDetailsButton = document.getElementById("submitCardDetails");
  
    paymentMethod.addEventListener("change", function() {
      if (paymentMethod.value === "card") {
        cardDetails.style.display = "block";
      } else {
        cardDetails.style.display = "none";
      }
    });
  
    showCardDetailsButton.addEventListener("click", function() {
      cardDetailsModal.style.display = "block";
    });
  
    closeButton.addEventListener("click", function() {
      cardDetailsModal.style.display = "none";
    });
  
    submitCardDetailsButton.addEventListener("click", function() {
      // Ici, vous pouvez ajouter la logique pour valider les détails de la carte
      cardDetailsModal.style.display = "none";
    });
  
    window.addEventListener("click", function(event) {
      if (event.target === cardDetailsModal) {
        cardDetailsModal.style.display = "none";
      }
    });
  });
  document.getElementById('paymentForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const cardDetailsModal = document.getElementById('cardDetailsModal');
    cardDetailsModal.style.display = 'none';
    const messageContainer = document.querySelector('.message');
    messageContainer.style.display = 'block';
});
