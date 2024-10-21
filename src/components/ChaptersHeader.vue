<script setup>
import SpeedDial from "./SpeedDial.vue";
import { reactive, ref, nextTick, onMounted, getCurrentInstance } from "vue";
import { useTheme } from "vuetify";

const theme = useTheme();
const emit = defineEmits(["add-chapter"]);
const props = defineProps({
  isChaptersEmpty: Boolean,
});

const primaryColor = theme.current.value.colors.primary;
const backgroundColor = theme.current.value.colors.background;

const titleTextInput = ref(null);
const isTitleEditEnabled = ref(false);
const title = ref("Diary title");
const dateFrom = ref("2023-01-09");
const dateTo = ref("2023-12-02");

function dates() {
  return dateFrom.value + " - " + dateTo.value;
}

async function toggleTitleEdit() {
  isTitleEditEnabled.value = !isTitleEditEnabled.value;
  if (isTitleEditEnabled.value) {
    await nextTick();
    titleTextInput.value.focus();
  }
}

function toggleTitleEditOff() {
  isTitleEditEnabled.value = false;
}

function addChapterAndEdit() {
  emit("add-chapter");
}

onMounted(() => {
  console.log(primaryColor);
});
</script>

<template>
  <v-container fluid class="bg-secondary pb-8">
    <v-row no-gutters justify="center">
      <v-col cols="2">
        <speed-dial />
      </v-col>

      <v-col
        v-if="isTitleEditEnabled"
        class="d-flex justify-center text-h4 cursor-text"
      >
        <v-text-field
          v-model="title"
          color="primary"
          label="Enter title"
          variant="outlined"
          class="diary-header-centered-text"
          hide-details
          density="compact"
          v-click-outside="toggleTitleEditOff"
          ref="titleTextInput"
        ></v-text-field>
      </v-col>

      <v-col
        v-else
        class="d-flex justify-center text-h4 cursor-text"
        @click="toggleTitleEdit"
      >
        {{ title }}
      </v-col>

      <v-col cols="2" class="d-flex align-center justify-end">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="14"
          width="14"
          viewBox="0 0 512 512"
          class="cursor-pointer"
          @click="toggleTitleEdit"
        >
          <path
            :fill="backgroundColor"
            d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"
          />
        </svg>
      </v-col>
    </v-row>
    <v-container class="text-center text-disabled">
      {{ dates() }}
    </v-container>

    <div class="d-flex justify-center">
      <v-btn
        v-if="isChaptersEmpty"
        color="primary"
        class="mt-3 pa-0"
        size="xl"
        rounded="xl"
        @click="addChapterAndEdit"
      >
        <div class="d-flex ga-2 justify-center align-center px-3">
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
          Write your first chapter
        </div>
      </v-btn>
      <template v-else>
        <v-btn class="mx-3">
          <template v-slot:prepend>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="20"
              width="25"
              viewBox="0 0 640 512"
            >
              <path
                :fill="primaryColor"
                d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-167l80 80c9.4 9.4 24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-39 39L344 184c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 134.1-39-39c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9z"
              />
            </svg>
          </template>
          Download</v-btn
        >
        <v-btn class="mx-3">
          <template v-slot:prepend>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="20"
              width="20"
              viewBox="0 0 512 512"
            >
              <path
                :fill="primaryColor"
                d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"
              />
            </svg>
          </template>
          Send to E-mail</v-btn
        >
      </template>
    </div>

    <v-fab
      v-if="!isChaptersEmpty"
      extended
      location="bottom end"
      absolute
      offset
      color="primary"
      class="new-chapter-button"
      @click="addChapterAndEdit"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="20"
        width="17.5"
        viewBox="0 0 448 512"
        class="mr-2"
      >
        <path
          fill="#ffffff"
          d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"
        />
      </svg>
      <span>NEW CHAPTER</span>
    </v-fab>
  </v-container>

  <v-divider :thickness="1" class="border-opacity-75"></v-divider>
</template>

<style>
.diary-header-centered-text input {
  text-align: center;
  font-size: 2.125rem;
}
.new-chapter-button {
  bottom: -30px;
}
</style>