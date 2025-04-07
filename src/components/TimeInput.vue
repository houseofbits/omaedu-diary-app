<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  id: String,
  modelValue: { String },
});
const emit = defineEmits(["update:modelValue", "is-open"]);

const isMenuOpen = ref(false);
const time = ref();

function validateTime(time) {
  const timeReg = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
  return time.match(timeReg);
}

const displayTime = computed(() => {
  if (time.value && validateTime(time.value)) {
    return time.value;
  } else {
    return "time";
  }
});

function close() {
  isMenuOpen.value = false;
}

watch(
  () => isMenuOpen.value,
  () => {
    emit("is-open", isMenuOpen.value);
  }
);

watch(
  () => time.value,
  () => {
    emit("update:modelValue", time.value);
  }
);

watch(
  () => props.modelValue,
  () => {
    if (props.modelValue && validateTime(props.modelValue)) {
      time.value = props.modelValue;
    }
  },
  { immediate: true }
);
</script>

<template>
  <v-btn
    :id="'menu-activator-' + id"
    variant="tonal"
    class="d-flex justify-start"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      height="14"
      width="14"
      viewBox="0 0 512 512"
      class="mr-2"
    >
      <path
        fill="black"
        d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"
      />
    </svg>
    {{ displayTime }}
  </v-btn>

  <v-menu
    v-model="isMenuOpen"
    :activator="'#menu-activator-' + id"
    :close-on-content-click="false"
  >
    <v-confirm-edit v-model="time" @save="close" @cancel="close">
      <template v-slot:default="{ model: proxyModel, actions }">
        <v-time-picker v-model="proxyModel.value" format="24hr">
          <template v-slot:actions>
            <component class="pa-0 ma-0" :is="actions"></component>
          </template>
        </v-time-picker>
      </template>
    </v-confirm-edit>
  </v-menu>
</template>

<style scoped>
:deep(.v-picker__actions) {
  margin: 0 !important;
  padding: 0 !important;
  margin-top: 16px !important;
}
:deep(.v-time-picker) {
  padding: 12px !important;
}
</style>