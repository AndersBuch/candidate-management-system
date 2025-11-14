<script setup>
import Button from '@/components/atoms/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { computed, ref } from 'vue'

const props = defineProps({
  index: Number,
  name: String,
  phone: String,
  email: String,
  status: String,
  linkedinUrl: String,
  isActive: Boolean   // <-- prop fra parent
})

const emit = defineEmits(['edit', 'statusClick', 'rowClick'])

function handleClick() {
  emit('rowClick')  // sender besked til parent om, at denne række blev klikket
}

function openLinkedin() {
  if (props.linkedinUrl) window.open(props.linkedinUrl, '_blank')
}

function onEdit() { emit('edit') }

function getStatusLabel(status) {
  switch (status?.toLowerCase()) {
    case 'accepted': return 'Accepteret'
    case 'pending': return 'Afventer'
    case 'contact': return 'Kontakt'
    case 'rejected': return 'Afvist'
    default: return status || 'Ukendt'
  }
}

const rowClass = computed(() => (props.index % 2 === 0 ? 'rowEven' : 'rowOdd'))
</script>

<template>
  <div
    class="tableRow"
    :class="[rowClass, { activeRow: isActive }]"
    @click="handleClick"
  >
    <div class="col colName">
      <div class="avatar">
        <BasicIconAndLogo
          :name="isActive ? 'UserWhite' : 'User'"
          :iconSize="true"
        />
      </div>
      <div class="nameText">{{ name }}</div>
    </div>

    <div class="col colPhone">{{ phone }}</div>
    <div class="col colEmail">{{ email }}</div>

    <Button
      v-if="status"
      :type="status.toLowerCase()"  
      :label="getStatusLabel(status)"
      :aria-label="getStatusLabel(status)"
      @click.stop="$emit('statusClick', { name, status })"
    />

    <div class="col colActions">
      <BasicIconAndLogo
        :name="isActive ? 'LinkinIconWhite' : 'LinkinIcon'"
        :iconSize="true"
        class="iconBtn linkedin"
        role="button"
        tabindex="0"
        aria-label="LinkedIn"
        @click.stop="openLinkedin"
      />

      <BasicIconAndLogo
        :name="isActive ? 'EditWhite' : 'Edit'"
        :iconSize="true"
        class="iconBtn edit"
        role="button"
        tabindex="0"
        :aria-label="isActive ? 'Aktiv' : 'Rediger'"
        @click.stop="onEdit"
      />
    </div>
  </div>
</template>

<style scoped lang="scss">
.tableRow {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr;
  align-items: center;
  gap: 12px;
  padding: 18px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.25s ease-in-out;

  &:hover {
    transform: scale(1.01);
  }
}

.rowEven {
  background: $sekundareBlue;
}

.rowOdd {
  background: $whiteColor;
}

.activeRow {
  background-color: $primaryBlue !important;
  color: $whiteColor;
  transition: all 0.2s ease-in-out;
}

.col {
  display: flex;
  align-items: center;
}

.colName {
  gap: 21px;
  @include bodyText;
}

.avatar {
  display: flex;
}


.colPhone,
.colEmail {
  @include bodyText;
}


.colActions {
  justify-content: flex-end;
  gap: 12px;
}

.iconBtn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s ease, opacity 0.2s ease;

  &:hover {
    transform: scale(1.1);
    opacity: 0.9;
  }
}
</style>
