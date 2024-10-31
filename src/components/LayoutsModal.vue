<script setup>
import { ref, watch } from "vue";
import { PAGE_LAYOUTS } from "../constants/pageLayouts";

const props = defineProps({
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue", "select"]);

const localIsOpen = ref(props.modelValue);

function confirm(value) {
  emit("select", value);
  localIsOpen.value = false;
}

watch(
  () => props.modelValue,
  () => {
    localIsOpen.value = props.modelValue;
  }
);

watch(
  () => localIsOpen.value,
  () => {
    emit("update:modelValue", localIsOpen.value);
  }
);
</script>

<template>
  <v-dialog v-model="localIsOpen" width="auto">
    <v-card max-width="400" title="Select page layout">
      <v-sheet class="d-flex flex-wrap">
        <v-sheet
          v-for="layout in Object.keys(PAGE_LAYOUTS)"
          :key="layout"
          class="ma-2 pa-2 elevation-3"
          @click="confirm(layout)"
        >
          {{ layout }}
        </v-sheet>
      </v-sheet>

      <template v-slot:actions>
        <v-spacer></v-spacer>
        <v-btn class="ms-auto" @click="localIsOpen = false">Cancel</v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

