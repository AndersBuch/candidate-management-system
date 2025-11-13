<script setup>
import InlineSvg from 'vue-inline-svg'
import { computed } from 'vue'

const props = defineProps({
  name: String,
  selected: Boolean,
  large: Boolean,
  huge: Boolean,
  small: Boolean,
  exstraSmall: Boolean,
  xxSmall: Boolean
})


const filePath = computed(() => `${import.meta.env.BASE_URL}icons/${props.name}.svg`);

</script>

<template>
  <InlineSvg
    :src="filePath"
    :title="props.name"
    keep-during-loading
    :class="{
      selected: props.selected,
      large: props.large,
      huge: props.huge,
      small: props.small,
      exstraSmall: props.exstraSmall,
      xxSmall: props.xxSmall
    }"
  />
</template>

<style scoped lang="scss">
svg {
  display: inline-block;
  stroke-width: 2;
  color: $black;
  &.selected {
    stroke-width: 2.5;
  }
  &.huge {
    width: 80px;
    height: 80px;
  }
  &.large {
    width: 150px;
    height: 51px;
  }
  &.small {
    width: 100px;
    height: 100px;
  }
    &.exstraSmall {
    width: 50px;
    height: 50px;
  }
      &.xxSmall {
    width: 40px;
    height: 40px;
  }
}
</style>
