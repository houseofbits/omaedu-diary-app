<script setup>
import { ref, computed, watch } from "vue";
import DateInput from "../components/DateInput.vue";
import TimeInput from "../components/TimeInput.vue";

const props = defineProps({
  id: String,
  modelValue: { String },
});
const emit = defineEmits(["update:modelValue", 'is-open']);

const dateTime = ref();
const date = ref();
const time = ref();

function isDateValid(dateStr) {
  return !isNaN(new Date(dateStr));
}

watch(
  () => date.value,
  () => {
    emit("update:modelValue", date.value + " " + (time.value ?? "00:00"));
  }
);

watch(
  () => time.value,
  () => {
    const now = new Date(Date.now());
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const dateStr =
      (date.value ?? now.toISOString().substring(0, 10)) +
      " " +
      (time.value ?? "00:00");

    dateTime.value = new Date(Date.parse(dateStr));

    emit("update:modelValue", dateStr);
  }
);

watch(
  () => props.modelValue,
  () => {
    if (props.modelValue && isDateValid(props.modelValue)) {
      const dateVal = new Date(Date.parse(props.modelValue));
      dateVal.setMinutes(dateVal.getMinutes() - dateVal.getTimezoneOffset());

      if (dateTime.value?.getTime() != dateVal.getTime()) {
        dateTime.value = dateVal;
        date.value = dateVal.toISOString().substring(0, 10);

        dateVal.setMinutes(dateVal.getMinutes() + dateVal.getTimezoneOffset());
        time.value =
          dateVal.getHours().toString().padStart(2, "0") +
          ":" +
          dateVal.getMinutes().toString().padStart(2, "0");
      }
    }
  },
  { immediate: true }
);

function emitIsOpen(isOpen) {
  emit('is-open', isOpen);
}
</script>

<template>
  <div class="d-flex">
    <date-input :id="id" v-model="date" button-class="mr-2" @is-open="emitIsOpen"></date-input>
    <time-input v-model="time" @is-open="emitIsOpen"></time-input>
  </div>
</template>