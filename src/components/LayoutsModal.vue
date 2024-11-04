<script setup>
import { ref, watch, computed } from "vue";
import { PAGE_LAYOUTS } from "../constants/pageLayouts";

const props = defineProps({
  modelValue: Boolean,
  isFirstPage: Boolean,
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

const availableLayouts = computed(() => {
  return Object.keys(PAGE_LAYOUTS).filter((elem) => {
    const layout = PAGE_LAYOUTS[elem];
    if (props.isFirstPage && layout.layout.isAdditionalPage) {
      return false;
    }

    return true;
  });
});

function getLayoutImage(layoutName) {
  return PAGE_LAYOUTS[layoutName].image;
}
</script>

<template>
  <v-dialog v-model="localIsOpen" width="auto">
    <v-card
      max-width="380"
      title="Select page layout"
      class="d-flex justify-center"
    >
      <v-sheet class="d-flex flex-wrap justify-right ga-4 mx-6">
        <v-hover
          v-for="layout in availableLayouts"
          :key="layout"
          v-slot="{ isHovering, props }"
          open-delay="50"
        >
          <v-card
            :class="{ 'on-hover': isHovering }"
            :elevation="isHovering ? 8 : 2"
            class="cursor-pointer border-thin"
            max-width="100"
            v-bind="props"
          >
            <v-img
              max-width="100"
              :width="100"
              aspect-ratio="16/9"
              cover
              :src="getLayoutImage(layout)"
              @click="confirm(layout)"
            />
          </v-card>
        </v-hover>
      </v-sheet>

      <template v-slot:actions>
        <v-spacer></v-spacer>
        <v-btn class="ms-auto" @click="localIsOpen = false">Cancel</v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>
