<script setup>
import Button from '@/components/atoms/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { computed } from 'vue'

const props = defineProps({
  index: { type: Number, required: true },        // sende fra parent (v-for index)
  name: { type: String, required: true },
  phone: { type: String, default: '' },
  email: { type: String, default: '' },
  status: { type: String, default: '' },         // tekst i badge
  statusVariant: { type: String, default: 'default' }, // 'accepted','pending','contact','rejected' -> styler badge
  linkedinUrl: { type: String, default: '' },
  avatar: { type: String, default: '' }          // evt. url eller tom for ikon
})

const emit = defineEmits(['edit'])

const rowClass = computed(() => (props.index % 2 === 0 ? 'row--even' : 'row--odd'))

function onEdit() { emit('edit') }
function openLinkedin() { if (props.linkedinUrl) window.open(props.linkedinUrl, '_blank') }
</script>

<template>
  <div class="tableRow" :class="rowClass">
    <div class="col col--name">
      <div class="avatar">
        <BasicIconAndLogo name="User" :large="true" />
      </div>
      <div class="nameText">{{ name }}</div>
    </div>

    <div class="col col--phone">{{ phone }}</div>
    <div class="col col--email">{{ email }}</div>

    <div class="col col--status">
      <span class="statusBadge" :data-variant="statusVariant">{{ status }}</span>
    </div>

    <div class="col col--actions">
      <button class="iconBtn linkedin" @click="openLinkedin" :disabled="!linkedinUrl" aria-label="LinkedIn">
        <!-- simple LinkedIn icon -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4h4v16H4zM8 4h4v16H8zM16 8c-1.657 0-3 1.343-3 3v7h4v-7c0-1.657 1.343-3 3-3s3 1.343 3 3v7h4v-7c0-4.418-3.582-8-8-8z" fill="#0A66C2"/></svg>
      </button>

      <button class="iconBtn edit" @click="onEdit" aria-label="Edit">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="#111"/></svg>
      </button>
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
  border-radius: 8px;
  margin-bottom: 8px;
  transition: transform .08s ease, box-shadow .08s ease;
}

.row--even { background: #e6f2ff; } /* lyseblå */
.row--odd  { background: #ffffff; } /* hvid */

.col { display:flex; align-items:center; }
.col--name { gap:12px; font-weight:600; color:#0f172a; }
.avatar { width:36px; height:36px; border-radius:50%; overflow:hidden; background:#fff; display:flex; align-items:center; justify-content:center; }
.avatar img { width:100%; height:100%; object-fit:cover; }

/* telefon / email mindre fed */
.col--phone, .col--email { color:#0f172a; font-weight:500; }

/* status badge */
.statusBadge {
  display:inline-block;
  padding:8px 14px;
  border-radius:999px;
  font-weight:700;
  font-size:.9rem;
  color:#fff;
}
.statusBadge[data-variant="accepted"] { background:#10b981; }
.statusBadge[data-variant="pending"]  { background:#fb923c; }
.statusBadge[data-variant="contact"]  { background:#ec4899; }
.statusBadge[data-variant="rejected"] { background:#ef4444; }
.statusBadge[data-variant="default"]  { background:#6b7280; }

/* actions (icons) */
.col--actions { justify-content:flex-end; gap:12px; }
.iconBtn {
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width:36px; height:36px;
  border-radius:8px;
  border: none;
  background: transparent;
  cursor:pointer;
}
.iconBtn.linkedin { background: rgba(10,102,194,0.08); border:1px solid rgba(10,102,194,0.12); }
.iconBtn.edit { background: rgba(0,0,0,0.04); border:1px solid rgba(0,0,0,0.06); }

.iconBtn:disabled { opacity:.45; cursor:not-allowed; }

/* just subtle hover */
.tableRow:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(2,6,23,0.06); }
</style>