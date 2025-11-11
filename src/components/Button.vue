<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: 'default',
  },
  showIcon: {
    type: Boolean,
    default: true,
  },
  iconName: {
    type: String,
    default: '',
  },
  active: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  ariaLabel: {
    type: String,
    required: true,
  },
})

const difftentButton = computed(() => ({
  //KANDIDAT SIDEN
  defaultButton: props.type === 'default' && !props.active,
  sekundareButton: props.type === 'sekundare' && !props.active,
  unAktivButton: props.type === 'ikkeAktiv' && !props.active,
  activeUnAktivButton: props.type === 'ikkeAktiv' && props.active,
  activeSecondaryButton: props.type === 'secondary' && props.active,
  smallButton: props.type === 'small' && !props.active,

  //DASHBOARD KNAPPER
  dashboardPrimaryButton: props.type === 'dashboardPrimary' && !props.active,
  smallDashboardButton: props.type === 'smalldashboard' && !props.active,

  //DASHBOARD STATUS
  acceptetButton: props.type === 'accept' && !props.active,
  afventerButton: props.type === 'afventer' && !props.active,
  kontaktesButton: props.type === 'kontaktes' && !props.active,
  afvistButton: props.type === 'afvist' && !props.active,

}))
</script>

<template>
  <button
    :class="[difftentButton, { disabledButton: props.disabled }]"
    :aria-label="props.ariaLabel"
  >
    <BasicIcon v-if="showIcon && iconName" :name="iconName" />
    <p>{{ label }}</p>
  </button>
</template>

<style scoped lang="scss">

.acceptetButton {
    background-color: $goodGreen;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: lighten($goodGreen, 10%);
    }
}

.afventerButton {
    background-color: $statusOrange;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: lighten($statusOrange, 10%);
    }
}

.kontaktesButton {
    background-color: $statusPurple;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: lighten($statusPurple, 10%);
    }
}

.afvistButton {
    background-color: $dangerRed;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: lighten($dangerRed, 10%);
    }
}


p {
  @include buttonText;
  color: $whiteColor;
}

button {
  display: flex;
  align-items: center;
  justify-content: center; 
  width: 215px;
  height: 45px;
  gap: 5px;
  border-radius: 15px;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
}

.defaultButton {
  background-color: $primaryBlue;
  color: $whiteColor;  
  border: none;

  &:hover {
    background-color: $hoverBlue;
  }
}

.sekundareButton {
  background-color: $whiteColor;
  color: $black;
  width: 115px;
  height: 30px;

  &:hover {
    background-color: $hoverLightBlue;
  }

  p {
    color: $black;
  }
}

.unAktivButton {
    background-color: $darkGrey;
    color: $whiteColor;
    border: none;
}

.activeUnAktivButton {
    background-color: $primaryBlue;
    color: $whiteColor;
    border: none;

  &:hover {
    background-color: $hoverBlue;
  }
}

.smallButton {
    background-color: $primaryBlue;
    color: $whiteColor;
    border: none;
    width: 120px;
    height: 37px;

  &:hover {
    background-color: $hoverBlue;
  }
}

.dashboardPrimaryButton {
    background-color: $primaryBlue;
    color: $whiteColor;
    border: none;
    width: 170px;
    height: 35px;

  &:hover {
    background-color: $hoverBlue;
  }
}

.smallDashboardButton {
    background-color: $primaryBlue;
    color: $whiteColor;
    border: none;
    width: 100px;
    height: 35px;

  &:hover {
    background-color: $hoverBlue;
  }
}

</style>
