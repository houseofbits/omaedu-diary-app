<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  id: String,
  modelValue: { String },
  buttonClass: String,
});
const emit = defineEmits(["update:modelValue"]);

const isMenuOpen = ref(false);
const date = ref(new Date());

function isDateValid(dateStr) {
  return !isNaN(new Date(dateStr));
}

function close() {
  isMenuOpen.value = false;
}

function getDisplayDate(dateVal) {
  const dateTime = new Date(dateVal);
  dateTime.setMinutes(dateTime.getMinutes() - dateTime.getTimezoneOffset());
  return dateTime.toISOString().slice(0, 10);
}

const displayTime = computed(() => {
  if (props.modelValue && isDateValid(props.modelValue)) {
    return getDisplayDate(date.value);
  } else {
    return "date";
  }
});

watch(
  () => date.value,
  () => {
    emit("update:modelValue", getDisplayDate(date.value));
  }
);

watch(
  () => props.modelValue,
  () => {
    if (props.modelValue && isDateValid(props.modelValue)) {
      date.value = new Date(Date.parse(props.modelValue));
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
    :class="buttonClass"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      height="14"
      width="12.25"
      viewBox="0 0 448 512"
      class="mr-2"
    >
      <path
        fill="black"
        d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256z"
      />
    </svg>
    <span>{{ displayTime }}</span>
  </v-btn>

  <v-menu
    v-model="isMenuOpen"
    :activator="'#menu-activator-' + id"
    :close-on-content-click="false"
  >
    <v-confirm-edit v-model="date" @save="close" @cancel="close">
      <template v-slot:default="{ model: proxyModel, actions }">
        <v-date-picker v-model="proxyModel.value">
          <template v-slot:actions>
            <component :is="actions"></component>
          </template>
        </v-date-picker>
      </template>
    </v-confirm-edit>
  </v-menu>
</template>