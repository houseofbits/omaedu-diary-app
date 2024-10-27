<script setup>
import { ref, nextTick, watch } from "vue";
import { useTheme } from "vuetify";

const theme = useTheme();
const emit = defineEmits(["close", "change-tab", "delete"]);
const props = defineProps({
  title: String,
});

const backgroundColor = theme.current.value.colors.background;
const tab = ref(null);
const isDeleteConfirmationModalOpen = ref(false);

watch(
  () => tab.value,
  () => {
    emit("change-tab", tab.value);
  }
);
</script>

<template>
  <v-container fluid class="bg-secondary">
    <v-row>
      <v-col cols="2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="20"
          width="17.5"
          viewBox="0 0 448 512"
          class="ml-3 cursor-pointer"
          @click="emit('close')"
        >
          <path
            :fill="backgroundColor"
            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
          />
        </svg>
      </v-col>

      <v-col class="d-flex justify-center text-h5 cursor-text">
        {{ title }}
      </v-col>

      <v-col cols="2" class="d-flex align-center justify-end">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="20"
          width="17.5"
          viewBox="0 0 448 512"
          class="ml-3 cursor-pointer"
          @click="emit('delete')"
        >
          <path
            :fill="backgroundColor"
            d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
          />
        </svg>
      </v-col>
    </v-row>
    <v-row class="d-flex justify-center">
      <v-tabs
        v-model="tab"
        align-tabs="center"
        color="deep-purple-accent-4"
        stacked
      >
        <v-tab :value="1">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24"
            width="24"
            viewBox="0 0 512 512"
            class="my-2"
          >
            <path
              :fill="backgroundColor"
              d="M278.5 215.6L23 471c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l74.8-74.8c7.4 4.6 15.3 8.2 23.8 10.5C200.3 452.8 270 454.5 338 409.4c12.2-8.1 5.8-25.4-8.8-25.4l-16.1 0c-5.1 0-9.2-4.1-9.2-9.2c0-4.1 2.7-7.6 6.5-8.8l97.7-29.3c3.4-1 6.4-3.1 8.4-6.1c4.4-6.4 8.6-12.9 12.6-19.6c6.2-10.3-1.5-23-13.5-23l-38.6 0c-5.1 0-9.2-4.1-9.2-9.2c0-4.1 2.7-7.6 6.5-8.8l80.9-24.3c4.6-1.4 8.4-4.8 10.2-9.3C494.5 163 507.8 86.1 511.9 36.8c.8-9.9-3-19.6-10-26.6s-16.7-10.8-26.6-10C391.5 7 228.5 40.5 137.4 131.6C57.3 211.7 56.7 302.3 71.3 356.4c2.1 7.9 12 9.6 17.8 3.8L253.6 195.8c6.2-6.2 16.4-6.2 22.6 0c5.4 5.4 6.1 13.6 2.2 19.8z"
            />
          </svg>
          <span class="mb-3">Write</span>
        </v-tab>
        <v-tab :value="2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24"
            width="24"
            viewBox="0 0 448 512"
            class="my-2"
          >
            <path
              :fill="backgroundColor"
              d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"
            />
          </svg>
          <span class="mb-3">Layout</span>
        </v-tab>
        <v-tab :value="3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24"
            width="24"
            viewBox="0 0 576 512"
            class="my-2"
          >
            <path
              :fill="backgroundColor"
              d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
            />
          </svg>
          <span class="mb-3">Preview</span>
        </v-tab>
      </v-tabs>
    </v-row>
  </v-container>

  <v-divider :thickness="1" class="border-opacity-75"></v-divider>
</template>

<style>
.chapter-header-centered-text input {
  text-align: center;
  font-size: 1.5rem;
}
</style>