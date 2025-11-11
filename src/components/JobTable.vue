<script setup>
import Button from '@/components/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
</script>

<template>

<div class="job-table-container">
  <table class="jobs">
    <colgroup>
      <col style="width:32%">  <!-- Firma -->
      <col style="width:26%">  <!-- Stilling -->
      <col style="width:22%">  <!-- Branche -->
      <col style="width:14%">  <!-- Geografi -->
      <col class="col-cta">     <!-- CTA -->
    </colgroup>

    <thead>
      <tr>
        <th>Firma</th>
        <th>Stilling</th>
        <th>Branche</th>
        <th>Geografi</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td class="cell-firma">
          <BasicIconAndLogo name="LPSLogo" :exstraSmall="true" />
          WSA A/S
        </td>
        <td>Værktøjsmager</td>
        <td>Præcisionsplast</td>
        <td>København</td>
        <td class="cell-cta">
          <Button label="Søg job" type="small" aria-label="Søg job" />
        </td>
      </tr>
      <tr>
        <td class="cell-firma">
          <BasicIconAndLogo name="LPSLogo" :exstraSmall="true" />
          WSA A/S
        </td>
        <td>Værktøjsmager</td>
        <td>Præcisionsplast</td>
        <td>København</td>
        <td class="cell-cta">
          <Button label="Søg job" type="small" aria-label="Søg job" />
        </td>
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
  margin-bottom: 40px;
}

.jobs {
  width: 1450px;
  border-radius: 15px;
  border-collapse: separate;
  border-spacing: 0;
  overflow: hidden;
}

.jobs th,
.jobs td {
  padding: 2rem 3rem;
  text-align: left;
  vertical-align: middle;
  color: $black;
}

/* header look */
.jobs thead th { background: $tableHeader; @include boldBodyText; font-size: 20px; };
.jobs tbody td {  @include bodyText;}

/* first cell: icon + text aligned */
.jobs .cell-firma {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* last column: shrink to content and align right */
.jobs .col-cta,
.jobs .cell-cta,
.jobs th:last-child,
.jobs td:last-child {
  width: 1%;
  white-space: nowrap;
  text-align: right;
  padding-right: 1rem;
}

/* hover/stripe (optional) */
.jobs tbody tr:hover td {
  background: $hoverLightBlue !important;
}
.jobs tbody tr:nth-child(even) td { background: $lightGrey; }

</style>