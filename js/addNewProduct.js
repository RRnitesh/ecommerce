document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("productForm");
  const submitButton = form.querySelector('button[type="submit"]'); // Get the submit button

  form.addEventListener("submit", function (event) {
      event.preventDefault(); // Prevent default form submission

      submitButton.disabled = true; // Disable button while processing

      const formData = new FormData(form);

      fetch("../php/ADDitem.php", {
          method: "POST",
          body: formData
      })
      .then((response) => response.json())
      .then((data) => {
          if (data.status === true) {
              const productModal = document.getElementById("productModal");
              const modal = bootstrap.Modal.getInstance(productModal);
              if (modal) modal.hide(); // Hide modal if it exists

              alert(data.message); // Show success message
              form.reset(); // Reset the form
          } else {
              alert("Product cannot be added: " + data.message);
          }
      })
      .catch((error) => {
          console.error("Error adding product:", error);
          alert("An error occurred while adding the product.");
      })
      .finally(() => {
          submitButton.disabled = false; // Re-enable the submit button after processing
      });
  });
});
