<script setup>
import Button from '@/components/Button.vue'

</script>

<template>

<div class="job-table-container">
  <table>
  <thead>
    <tr>
      <th colspan="2">Firma</th>
      <th colspan="2">Stilling</th>
      <th colspan="2">Branche</th>
      <th colspan="1">Geografi</th>
      <th colspan="1"></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2">WSA A/S</td>
      <td colspan="2">Værktøjsmager</td>
      <td colspan="2">Præcisionsplast</td>
      <td colspan="1">København</td>
      <td colspan="1"><Button label="Søg job" type="small" aria-label="Søg job" /></td>
    </tr>

  </tbody>
  </table>
</div>

</template>

<style scoped lang="scss">

.job-table-container {
  display: flex;
  justify-content: center;
  width: 100%;
  


  table {
    width: 1450px;
    border-collapse: collapse;
    margin-bottom: 40px;
    overflow: hidden;
    border-radius: 15px;
    border: 1px solid #e5eaf2;
  }

  th, td {
    padding: 2rem 3rem; 
    text-align: left;
    color: $black;
    
  }

  th {
    @include boldBodyText;
    font-size: 20px;
    background-color: $tableHeader;
  }

  td {
    @include bodyText;
  }

  tbody tr:hover td {
    background-color: $hoverLightBlue !important;
  }
  tr:nth-child(even) {
    background-color: $lightGrey;
  }

}

.job-table-container table { width:1450px; }

.job-table-container th:last-child,
.job-table-container td:last-child {
  width: 1%;              /* override the 1/8 share */
  white-space: nowrap;
  text-align: right;
  padding-right: 16px;
}

.job-table-container th, .job-table-container td { padding: 2rem 3rem; }
.job-table-container th:last-child, .job-table-container td:last-child { padding-right: 1rem; }

</style>