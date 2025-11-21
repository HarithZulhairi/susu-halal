@extends('layouts.labtech')

@section('title', 'Milk Processing')

@section('content')
<link rel="stylesheet" href="{{ asset('css/labtech_process-milk.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
  <div class="main-content">

    <div class="page-header">
      <h1>Add Record Details</h1>
      <button class="btn-back"><i class="fas fa-arrow-left"></i> Back</button>
    </div>

    {{-- === Tab Navigation === --}}
    <div class="stage-tabs">
      <button class="stage-tab active" data-stage="first">First Stage</button>
      <button class="stage-tab" data-stage="second">Second Stage</button>
      <button class="stage-tab" data-stage="third">Third Stage</button>
    </div>

    {{-- === First Stage === --}}
    <div class="process-card stage-content active" id="first-stage">
      <h2>First Stage</h2>
      <h3>Screening</h3>
      <img src="{{ asset('images/lab_screening.jpg') }}" alt="Screening" style="width: 270px; height: auto;">
      <div class="form-grid">
        <div>
          <label>Start Date</label>
          <input type="date" placeholder="Enter Date">
        </div>
        <div>
          <label>End Date</label>
          <input type="date" placeholder="Enter Date">
        </div>
        <div>
          <label>Start Time</label>
          <input type="time">
        </div>
        <div>
          <label>End Time</label>
          <input type="time">
        </div>
      </div>
      <p class="time-status completed">Time left: COMPLETED</p>
      
      {{-- Data Table --}}
      <div class="data-table-container">
        <div class="table-header-info">
          <span class="animals-selected" id="first-count">4 Animals Selected, 4 Data Lines</span>
          <button class="btn-add-row" onclick="addTableRow('first')">
            <i class="fas fa-plus"></i> Add Row
          </button>
        </div>
        <table class="data-table" id="first-table">
          <thead>
            <tr>
              <th>Ctrl No</th>
              <th>Cow Name</th>
              <th>Daily Milk</th>
              <th>Fat</th>
              <th>Pro</th>
              <th>Scc</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td contenteditable="true">2</td>
              <td contenteditable="true">ANDROME</td>
              <td contenteditable="true">2.4</td>
              <td contenteditable="true">5.81</td>
              <td contenteditable="true">4.78</td>
              <td contenteditable="true">48</td>
              <td class="actions">
                <button class="btn-delete-row" onclick="deleteTableRow(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td contenteditable="true">9</td>
              <td contenteditable="true">SIERRA</td>
              <td contenteditable="true">2.6</td>
              <td contenteditable="true">6.26</td>
              <td contenteditable="true">4.62</td>
              <td contenteditable="true">105</td>
              <td class="actions">
                <button class="btn-delete-row" onclick="deleteTableRow(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td contenteditable="true">10</td>
              <td contenteditable="true">RASPBRY</td>
              <td contenteditable="true">3.0</td>
              <td contenteditable="true">6.24</td>
              <td contenteditable="true">4.44</td>
              <td contenteditable="true">45</td>
              <td class="actions">
                <button class="btn-delete-row" onclick="deleteTableRow(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td contenteditable="true">11</td>
              <td contenteditable="true">MAYPOP</td>
              <td contenteditable="true">3.0</td>
              <td contenteditable="true">6.18</td>
              <td contenteditable="true">4.03</td>
              <td contenteditable="true">82</td>
              <td class="actions">
                <button class="btn-delete-row" onclick="deleteTableRow(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="button-row">
        <button class="btn-back-nav"><i class="fas fa-arrow-left"></i> Back</button>
        <button class="btn-next" onclick="switchStage('second')">Next Stage <i class="fas fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- === Second Stage === --}}
    <div class="process-card stage-content" id="second-stage">
      <h2>Second Stage</h2>
      <h3>Collecting</h3>
      <img src="{{ asset('images/lab_collecting.jpg') }}" alt="Collecting" style="width: 270px; height: auto;">
      <div class="form-grid">
        <div>
          <label>Start Date</label>
          <input type="date">
        </div>
        <div>
          <label>End Date</label>
          <input type="date">
        </div>
        <div>
          <label>Start Time</label>
          <input type="time">
        </div>
        <div>
          <label>End Time</label>
          <input type="time">
        </div>
      </div>
      <p class="time-status active">Time left: 23:48:10</p>

      <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('first')"><i class="fas fa-arrow-left"></i> Previous</button>
        <button class="btn-next" onclick="switchStage('third')">Next Stage <i class="fas fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- === Third Stage === --}}
    <div class="process-card stage-content" id="third-stage">
      <h2>Third Stage</h2>
      <h3>Pasteurizing</h3>
      <img src="{{ asset('images/lab_pasteurizing.jpg') }}" alt="Pasteurizing" style="width: 270px; height: auto;">
      <div class="form-grid">
        <div>
          <label>Start Date</label>
          <input type="date">
        </div>
        <div>
          <label>End Date</label>
          <input type="date">
        </div>
        <div>
          <label>Start Time</label>
          <input type="time">
        </div>
        <div>
          <label>End Time</label>
          <input type="time">
        </div>
      </div>
      <p class="time-status pending">Time left: NOT STARTED YET</p>

      <div class="button-row">
        <button class="btn-back-stage" onclick="switchStage('second')"><i class="fas fa-arrow-left"></i> Previous</button>
        <a href="{{ route('labtech.labtech_manage-milk-records') }}" class="btn-next">
          Submit <i class="fas fa-check"></i>
        </a>
      </div>
    </div>

  </div>
</div>

<script>
// Switch between stages
function switchStage(stage) {
  document.querySelectorAll('.stage-content').forEach(content => {
    content.classList.remove('active');
  });
  
  document.querySelectorAll('.stage-tab').forEach(tab => {
    tab.classList.remove('active');
  });
  
  document.getElementById(stage + '-stage').classList.add('active');
  document.querySelector(`[data-stage="${stage}"]`).classList.add('active');
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Tab click handlers
document.querySelectorAll('.stage-tab').forEach(tab => {
  tab.addEventListener('click', function() {
    const stage = this.getAttribute('data-stage');
    switchStage(stage);
  });
});

// Add new row to table
function addTableRow(stage) {
  const table = document.getElementById(stage + '-table').getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();
  
  // Add editable cells
  for (let i = 0; i < 6; i++) {
    const cell = newRow.insertCell(i);
    cell.contentEditable = "true";
    cell.textContent = "";
    
    // Add placeholder styling
    if (i === 0) cell.textContent = "0";
    else if (i === 1) cell.textContent = "COW NAME";
    else cell.textContent = "0.0";
  }
  
  // Add delete button cell
  const actionsCell = newRow.insertCell(6);
  actionsCell.className = "actions";
  actionsCell.innerHTML = `
    <button class="btn-delete-row" onclick="deleteTableRow(this)">
      <i class="fas fa-trash"></i>
    </button>
  `;
  
  // Update count
  updateRowCount(stage);
  
  // Focus on first cell
  newRow.cells[0].focus();
}

// Delete row from table
function deleteTableRow(button) {
  const row = button.closest('tr');
  const table = row.closest('table');
  const stage = table.id.replace('-table', '');
  
  if (table.rows.length > 2) { // Keep at least header + 1 row
    row.remove();
    updateRowCount(stage);
  } else {
    alert('At least one data row is required.');
  }
}

// Update row count display
function updateRowCount(stage) {
  const table = document.getElementById(stage + '-table');
  const rowCount = table.getElementsByTagName('tbody')[0].rows.length;
  const countElement = document.getElementById(stage + '-count');
  countElement.textContent = `${rowCount} Animals Selected, ${rowCount} Data Lines`;
}

// Add enter key navigation for cells
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.data-table tbody td[contenteditable="true"]').forEach(cell => {
    cell.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const nextCell = this.nextElementSibling;
        if (nextCell && nextCell.contentEditable === "true") {
          nextCell.focus();
        }
      }
    });
  });
});
</script>

@endsection