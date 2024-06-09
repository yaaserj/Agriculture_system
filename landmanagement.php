<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Land Management</title>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Global styles */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
      background-color: #f5f5f5;
    }

    .container {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h1 {
      margin: 0;
      font-size: 24px;
    }

    .navigation {
      display: flex;
      flex-direction: column;
      gap: 10px;
      padding: 10px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
      border-radius: 5px;
    }

    .navigation a {
      text-decoration: none;
      color: #333;
      padding: 10px;
      border-bottom: 1px solid #ddd;
      border-radius: 3px;
      transition: background-color 0.3s ease;
    }

    .navigation a:hover {
      background-color: #eee;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 20px;
    }

    .table-container {
      overflow-x: auto;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    .actions {
      display: flex;
      gap: 5px;
    }

    .actions a {
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 3px;
      transition: background-color 0.3s ease;
    }

    .actions a.edit {
      background-color: #ffc107;
      color: #333;
    }

    .actions a.edit:hover {
      background-color: #ffa800;
    }

    .actions a.delete {
      background-color: #dc3545;
      color: #fff;
    }

    .actions a.delete:hover {
      background-color: #c8233c;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 50%;
      border-radius: 5px;
      animation: slide-in 0.4s ease;
    }

    @keyframes slide-in {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .select2 {
      width: 100%;
    }

    button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background-color: #4CAF50;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #45a049;
    }

  </style>
</head>
<body>
  <div class="container">
    <header class="header">
      <a href="dashboard.html" style="color: white; text-decoration: none;">Dashboard</a>
      <h1>Agricultural Management System - Land Management</h1>
    </header>

    <nav class="navigation">
      <a href="#">Add New Land Parcel</a>
      <a href="#">Crops</a>  
    </nav>

    <main class="main-content">
      <h2>Land Parcels</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Parcel ID</th>
              <th>Location</th>
              <th>Size (ha)</th>
              <th>Soil Type</th>
              <th>Assigned Crops</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="land-data">
          </tbody>
        </table>
      </div>

      <div id="add-land-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Add New Land Parcel</h2>
          <form id="add-land-form">
            <div class="form-group">
              <label for="location">Location:</label>
              <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
              <label for="size">Size (ha):</label>
              <input type="number" step="0.01" id="size" name="size" required>
            </div>
            <div class="form-group">
              <label for="soil-type">Soil Type:</label>
              <input type="text" id="soil-type" name="soilType" required>
            </div>
            <button type="submit">Add Land Parcel</button>
          </form>
        </div>
      </div>

      <div id="edit-land-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Edit Land Parcel</h2>
          <form id="edit-land-form">
            <div class="form-group">
              <label for="edit-location">Location:</label>
              <input type="text" id="edit-location" name="location" required>
            </div>
            <div class="form-group">
              <label for="edit-size">Size (ha):</label>
              <input type="number" step="0.01" id="edit-size" name="size" required>
            </div>
            <div class="form-group">
              <label for="edit-soil-type">Soil Type:</label>
              <input type="text" id="edit-soil-type" name="soilType" required>
            </div>
            <button type="submit">Save Changes</button>
          </form>
        </div>
      </div>

      <div id="assign-crops-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Assign Crops</h2>
          <form id="assign-crops-form">
            <div class="form-group">
              <label for="assigned-crops">Select Crops:</label>
              <select id="assigned-crops" name="assignedCrops" multiple="multiple" required></select>
            </div>
            <button type="submit">Assign Crops</button>
          </form>
        </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        // Simulate fetching land data from a server (replace with your actual data fetching logic)
        const landParcels = [
          { id: 1, location: "Field A", size: 2.0, soilType: "Sandy Loam", assignedCrops: ["Corn", "Wheat"] },
          { id: 2, location: "Hillside Plot", size: 0.5, soilType: "Clayey", assignedCrops: ["Rice"] },
          // ... more land parcel data
        ];

        // Function to populate land data table
        function populateLandData(data) {
          const tableBody = document.getElementById('land-data');
          tableBody.innerHTML = '';  // Clear existing data

          data.forEach(parcel => {
            const assignedCropsList = parcel.assignedCrops.length > 0 ? parcel.assignedCrops.join(', ') : 'No crops assigned';
            const tableRow = document.createElement('tr');
            tableRow.innerHTML = `
              <td>${parcel.id}</td>
              <td>${parcel.location}</td>
              <td>${parcel.size}</td>
              <td>${parcel.soilType}</td>
              <td>${assignedCropsList}</td>
              <td class="actions">
                <a href="#" class="edit" data-id="${parcel.id}">Edit</a>
                <a href="#" class="assign-crops" data-id="${parcel.id}">Assign Crops</a>
              </td>
            `;
            tableBody.appendChild(tableRow);
          });
        }

        // Populate land data on page load
        populateLandData(landParcels);

        // Get modal elements
        const addLandModal = document.getElementById('add-land-modal');
        const editLandModal = document.getElementById('edit-land-modal');
        const assignCropsModal = document.getElementById('assign-crops-modal');
        const addLandForm = document.getElementById('add-land-form');
        const editLandForm = document.getElementById('edit-land-form');
        const assignCropsForm = document.getElementById('assign-crops-form');

        // Function to open add land modal
        function openAddLandModal() {
          addLandModal.style.display = 'block';
          addLandForm.reset();  // Clear form on open
        }

        // Function to close modals
        function closeModal(modal) {
          modal.style.display = 'none';
        }

        // Get close buttons from both modals
        const addLandModalClose = document.querySelector('#add-land-modal .close');
        const editLandModalClose = document.querySelector('#edit-land-modal .close');
        const assignCropsModalClose = document.querySelector('#assign-crops-modal .close');

        // Add event listeners for modal close buttons
        addLandModalClose.addEventListener('click', () => closeModal(addLandModal));
        editLandModalClose.addEventListener('click', () => closeModal(editLandModal));
        assignCropsModalClose.addEventListener('click', () => closeModal(assignCropsModal));

        // Add event listener for "Add New Land Parcel" link
        const addLandLink = document.querySelector('.navigation a:nth-child(1)');  // Select the first anchor in navigation
        addLandLink.addEventListener('click', openAddLandModal);

        // Function to handle add land form submission (replace with actual data storage logic)
        addLandForm.addEventListener('submit', (event) => {
          event.preventDefault(); // Prevent default form submission

          const newLandParcel = {
            id: Math.floor(Math.random() * 10000),  // Generate a temporary ID
            location: document.getElementById('location').value,
            size: document.getElementById('size').value,
            soilType: document.getElementById('soil-type').value,
            assignedCrops: [],
          };

          // Simulate adding data to server (replace with your actual logic)
          landParcels.push(newLandParcel);

          // Update table data
          populateLandData(landParcels);

          // Close add land modal
          closeModal(addLandModal);
        });

        // Function to open edit land modal and pre-populate data
        function openEditLandModal(parcelId) {
          const selectedParcel = landParcels.find(parcel => parcel.id === parcelId);
          if (selectedParcel) {
            document.getElementById('edit-location').value = selectedParcel.location;
            document.getElementById('edit-size').value = selectedParcel.size;
            document.getElementById('edit-soil-type').value = selectedParcel.soilType;
            editLandForm.dataset.landId = parcelId;  // Store land parcel ID for form submission
            editLandModal.style.display = 'block';
          }
        }

        // Function to handle edit land form submission (replace with actual data update logic)
        editLandForm.addEventListener('submit', (event) => {
          event.preventDefault(); // Prevent default form submission

          const landParcelId = parseInt(editLandForm.dataset.landId);
          const updatedLandParcel = {
            id: landParcelId,
            location: document.getElementById('edit-location').value,
            size: document.getElementById('edit-size').value,
            soilType: document.getElementById('edit-soil-type').value,
            assignedCrops: landParcels.find(parcel => parcel.id === landParcelId).assignedCrops,  // Preserve assigned crops
          };

          // Simulate updating data on server (replace with your actual logic)
          const parcelIndex = landParcels.findIndex(parcel => parcel.id === landParcelId);
          landParcels[parcelIndex] = updatedLandParcel;

          // Update table data
          populateLandData(landParcels);

          // Close edit land modal
          closeModal(editLandModal);
        });

        // Function to open assign crops modal and pre-populate data
        function openAssignCropsModal(parcelId) {
          const selectedParcel = landParcels.find(parcel => parcel.id === parcelId);
          if (selectedParcel) {
            $('#assigned-crops').val(selectedParcel.assignedCrops).trigger('change');
            assignCropsForm.dataset.landId = parcelId;  // Store land parcel ID for form submission
            assignCropsModal.style.display = 'block';
          }
        }

        // Function to handle assign crops form submission
        assignCropsForm.addEventListener('submit', (event) => {
          event.preventDefault(); // Prevent default form submission

          const landParcelId = parseInt(assignCropsForm.dataset.landId);
          const assignedCrops = $('#assigned-crops').val();

          // Update assigned crops for the selected parcel
          const parcelIndex = landParcels.findIndex(parcel => parcel.id === landParcelId);
          landParcels[parcelIndex].assignedCrops = assignedCrops;

          // Update table data
          populateLandData(landParcels);

          // Close assign crops modal
          closeModal(assignCropsModal);
        });

        // Add event listeners for edit and assign crops links in the table
        const landTable = document.getElementById('land-data');
        landTable.addEventListener('click', (event) => {
          const clickedElement = event.target;
          if (clickedElement.classList.contains('edit')) {
            const landParcelId = parseInt(clickedElement.dataset.id);
            openEditLandModal(landParcelId);
          } else if (clickedElement.classList.contains('assign-crops')) {
            const landParcelId = parseInt(clickedElement.dataset.id);
            openAssignCropsModal(landParcelId);
          }
        });

        // Initialize Select2 for crops selection
        $(document).ready(function() {
          $('#assigned-crops').select2({
            placeholder: "Select crops",
            allowClear: true
          });
        });

        // Sample crops data for Select2 (replace with your actual data)
        const cropsData = [
          { id: 'Corn', text: 'Corn' },
          { id: 'Wheat', text: 'Wheat' },
          { id: 'Rice', text: 'Rice' },
          { id: 'Soybeans', text: 'Soybeans' }
        ];

        // Populate crops data in Select2
        $('#assigned-crops').select2({
          data: cropsData
        });
      </script>
  </div>
</body>
</html>
