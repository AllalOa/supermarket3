<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demande de Produits au Magasinier</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .form-container {
      background-color: white;
      max-width: 800px;
      padding: 2rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      display: flex;
      gap: 1.5rem;
    }

    .product-list-container {
      flex: 1;
      background-color: #f8f9fa;
      padding: 1.5rem;
      border-radius: 8px;
      border: 1px solid #e9ecef;
    }

    .form-content {
      flex: 2;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    h2, h3, h4 {
      color: #2b2d42;
      margin: 0 0 1rem 0;
    }

    h2 {
      font-size: 1.5rem;
      font-weight: 600;
    }

    h3 {
      font-size: 1.25rem;
      font-weight: 500;
    }

    h4 {
      font-size: 1.1rem;
      font-weight: 500;
      color: #4a4c60;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    label {
      font-weight: 500;
      color: #4a4c60;
      margin-bottom: 0.25rem;
    }

    input, select {
      padding: 0.75rem;
      border: 1px solid #e9ecef;
      border-radius: 6px;
      background-color: white;
      transition: border-color 0.3s ease;
    }

    input:focus, select:focus {
      outline: none;
      border-color: #4361ee;
      box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
    }

    button {
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    button[type="submit"] {
      background-color: #4361ee;
      color: white;
      border: none;
    }

    button[type="submit"]:hover {
      background-color: #3b52cc;
    }

    button[type="reset"] {
      background-color: #dc3545;
      color: white;
      border: none;
    }

    button[type="reset"]:hover {
      background-color: #bb2d3b;
    }

    button[type="button"] {
      background-color: #2b2d42;
      color: white;
      border: none;
    }

    button[type="button"]:hover {
      background-color: #1a1b27;
    }

    table {
      width: 100%;
      margin: 1rem 0;
      border-collapse: collapse;
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    th, td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #e9ecef;
    }

    th {
      background-color: #f8f9fa;
      font-weight: 600;
      color: #2b2d42;
    }

    .total-container {
      margin-top: 1.5rem;
      padding: 1rem;
      background-color: #f8f9fa;
      border-radius: 6px;
      font-weight: 600;
      color: #2b2d42;
    }

    .product-list-actions {
      margin-top: 1.5rem;
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }

    /* Confirmation Page Styles */
    #confirmation-page {
      display: none;
      background-color: white;
      max-width: 800px;
      padding: 2rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      margin: 20px auto;
    }
    
    .signature-section {
      margin-top: 2rem;
      padding-top: 1rem;
      border-top: 2px solid #ccc;
    }
  </style>
</head>
<body>
  <!-- Original Form Container -->
  <div class="form-container">
    <div class="product-list-container">
      <h3>Liste des Produits Demandés</h3>
      <table id="product-list">
        <thead>
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix Unitaire (€)</th>
            <th>Total (€)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="total-container">
        <strong>Total de la Commande : </strong><span id="total-price">0.00</span> €
      </div>
      <div class="product-list-actions">
        <button type="button" onclick="window.print();">Imprimer la Demande</button>
      </div>
    </div>

    <div class="form-content">
      <h2>Demande de Produits</h2>
      <h4>Num Demande :</h4>
      <form id="request-form" method="get" action="your-server-endpoint">
        <label for="name">Nom du Demandeur :</label>
        <input type="text" id="name" name="name" required>

        <label for="destination">Destination :</label>
        <input type="text" id="destination" name="destination" required>

        <h4>Ajouter un produit</h4>
        <label for="product">Produit :</label>
        <input type="text" id="product" name="product" required>

        <label for="quantity">Quantité :</label>
        <input type="number" id="quantity" name="quantity" required min="1">

        <button type="button" id="add-product">Ajouter le Produit</button>

        <div class="form-actions">
          <button type="submit">Envoyer la Demande</button>
          <button type="button" id="finish-button">Finir</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirmation Page -->
  <div id="confirmation-page">
    <h2>Demande Finalisée</h2>
    <p><strong>Nom du Demandeur:</strong> <span id="confirmation-name"></span></p>
    
    <table>
      <thead>
        <tr>
          <th>Produit</th>
          <th>Quantité</th>
          <th>Prix Unitaire (€)</th>
          <th>Total (€)</th>
        </tr>
      </thead>
      <tbody id="confirmation-products">
      </tbody>
    </table>

    <div class="total-container">
      <strong>Total de la Commande : </strong><span id="confirmation-total">0.00</span> €
    </div>

    <div class="signature-section">
      <p>Signature : _________________________</p>
      <button onclick="window.print()" style="margin-top: 1rem;">Imprimer</button>
    </div>
  </div>

  <script>
    // Original Product Addition Logic
    const addProductButton = document.getElementById('add-product');
    const productList = document.getElementById('product-list').querySelector('tbody');
    const totalPriceElement = document.getElementById('total-price');

    addProductButton.addEventListener('click', () => {
      const product = document.getElementById('product').value;
      const quantity = document.getElementById('quantity').value;
      const pricePerUnit = 10; // Example fixed unit price
      const total = (quantity * pricePerUnit).toFixed(2);

      if (product && quantity > 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${product}</td>
          <td>${quantity}</td>
          <td>${pricePerUnit}</td>
          <td>${total}</td>
        `;
        productList.appendChild(row);

        const currentTotal = parseFloat(totalPriceElement.textContent) || 0;
        totalPriceElement.textContent = (currentTotal + parseFloat(total)).toFixed(2);

        document.getElementById('product').value = '';
        document.getElementById('quantity').value = '';
      } else {
        alert('Veuillez saisir un produit et une quantité valides.');
      }
    });

    // Confirmation Page Logic
    document.getElementById('finish-button').addEventListener('click', () => {
      // Hide original form
      document.querySelector('.form-container').style.display = 'none';
      
      // Show confirmation page
      const confirmationPage = document.getElementById('confirmation-page');
      confirmationPage.style.display = 'block';

      // Populate confirmation data
      document.getElementById('confirmation-name').textContent = 
        document.getElementById('name').value;

      // Clone products table
      const originalRows = document.querySelectorAll('#product-list tbody tr');
      const confirmationTbody = document.getElementById('confirmation-products');
      confirmationTbody.innerHTML = '';

      originalRows.forEach(row => {
        confirmationTbody.appendChild(row.cloneNode(true));
      });

      // Set total price
      document.getElementById('confirmation-total').textContent = 
        document.getElementById('total-price').textContent;
    });
  </script>
</body>
</html>