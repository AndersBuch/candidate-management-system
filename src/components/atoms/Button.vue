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

const emit = defineEmits(['click'])

const difftentButton = computed(() => ({
  //KANDIDAT SIDEN
  defaultButton: props.type === 'default' && !props.active,
  secondaryButton: props.type === 'secondary' && !props.active,
  unActiveButton: props.type === 'notActive' && !props.active,
  activeUnAktivButton: props.type === 'notActive' && props.active,
  activeSecondaryButton: props.type === 'secondaryActive' && props.active,
  smallButton: props.type === 'small' && !props.active,

  //DASHBOARD KNAPPER
  dashboardPrimaryButton: props.type === 'dashboardPrimary' && !props.active,
  smallDashboardButton: props.type === 'smalldashboard' && !props.active,

  //DASHBOARD STATUS
acceptedButton: props.type === 'accepted' && !props.active,
pendingButton: props.type === 'pending' && !props.active,
contactButton: props.type === 'contact' && !props.active,
rejectedButton: props.type === 'rejected' && !props.active,

}))
</script>

<template>
  <button
    :class="[difftentButton, { disabledButton: props.disabled }]"
    :aria-label="props.ariaLabel"
    :disabled="props.disabled"
    @click.stop="!props.disabled && emit('click')"
  >
    <BasicIconAndLogo v-if="showIcon && iconName" :name="iconName" />
    <p>{{ label }}</p>
  </button>
</template>

<style scoped lang="scss">

.disabledButton {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

.acceptedButton {
    background-color: $goodGreen;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: rgba($goodGreen, 0.9);
    }
}

.pendingButton {
    background-color: $statusOrange;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: rgba($statusOrange, 0.9);
        
    }
}

.contactButton {
    background-color: $statusPurple;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: rgba($statusPurple, 0.9);
    }
}

.rejectedButton {
    background-color: $dangerRed;
    border: none;
    width: 100px;
    height: 35px;

    &:hover {
        background-color: rgba($dangerRed, 0.9);
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

.secondaryButton {
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

.unActiveButton {
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
