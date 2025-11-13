<script setup>
import TableField from '@/components/dashboard/TableField.vue'

const rows = [
  { name:'Hans Hansen Ole', phone:'22283910', email:'kontaktmail@gmail.com', status:'Acceptet', statusVariant:'accepted', linkedin:'https://...' },
  { name:'Agnes Kofoed', phone:'55724901', email:'promailhe@hotmail.com', status:'Afventer', statusVariant:'pending', linkedin:'' },
  // ...
]

function onEdit(row) { console.log('edit', row) }
</script>

<template>
  <div>
    <TableField
      v-for="(r,i) in rows"
      :key="i"
      :index="i"
      :name="r.name"
      :phone="r.phone"
      :email="r.email"
      :status="r.status"
      :status-variant="r.statusVariant"
      :linkedin-url="r.linkedin"
      @edit="() => onEdit(r)"
    />
  </div>
</template>

<style lang="scss">

</style>