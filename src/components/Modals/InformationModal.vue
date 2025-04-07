<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const localIsOpen = ref(props.modelValue);

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
  <v-dialog v-model="localIsOpen" :fullscreen="$vuetify.display.xs" max-width="800">
    <v-card title="Welcome to your private diary">
      <template v-slot:title>
        <div class="d-flex justify-space-between">
          <div>Welcome to your private diary</div>
          <div>
            <v-btn class="ms-auto" variant="text" @click="localIsOpen = false">
              Continue

              <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512" class="ml-2">
                <path :fill="backgroundColor"
                  d="M278.5 215.6L23 471c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l74.8-74.8c7.4 4.6 15.3 8.2 23.8 10.5C200.3 452.8 270 454.5 338 409.4c12.2-8.1 5.8-25.4-8.8-25.4l-16.1 0c-5.1 0-9.2-4.1-9.2-9.2c0-4.1 2.7-7.6 6.5-8.8l97.7-29.3c3.4-1 6.4-3.1 8.4-6.1c4.4-6.4 8.6-12.9 12.6-19.6c6.2-10.3-1.5-23-13.5-23l-38.6 0c-5.1 0-9.2-4.1-9.2-9.2c0-4.1 2.7-7.6 6.5-8.8l80.9-24.3c4.6-1.4 8.4-4.8 10.2-9.3C494.5 163 507.8 86.1 511.9 36.8c.8-9.9-3-19.6-10-26.6s-16.7-10.8-26.6-10C391.5 7 228.5 40.5 137.4 131.6C57.3 211.7 56.7 302.3 71.3 356.4c2.1 7.9 12 9.6 17.8 3.8L253.6 195.8c6.2-6.2 16.4-6.2 22.6 0c5.4 5.4 6.1 13.6 2.2 19.8z" />
              </svg>
            </v-btn>
          </div>
        </div>
      </template>

      <v-container class="px-6">
        <p class="mb-2">
          This diary allows you to keep memories and useful information about the waiting time. It can be useful in many
          different ways. It can be just nice memories, useful information about how the pregnancy is going - about your
          health and the health of the baby, or emotional things about the pregnancy.
        </p>
        <p class="mb-2">
          Here you can collect all the medical information about doctor's appointments, health indicators, and other
          measures for your and your baby's health.
        </p>
        <p class="mb-2">
          In other sections you can write about your emotions, thoughts, wishes, and well-being, you can write letters
          to your future baby, talk about your dreams, and how you imagine spending time with him/her.
        </p>
        <p class="mb-2">
          You can also collect information about the day's activities - walks, concert visits, outings, etc., or a nice
          day at home, meeting friends, etc.
        </p>
        <p class="mb-10">
          This will also help you be more aware this time, get used to the new situation, and feel that you are not
          alone but already with your future baby.
        </p>

        <h4 class="mb-1">
          Information
        </h4>

        <p class="mb-6">
          To write a new story into Your diary press
          <span class="text-button">START A NEW DIARY</span> and then <span class="text-button">NEW CHAPTER</span>
          buttons.
        </p>

        <h4 class="mb-1">
          <svg xmlns="http://www.w3.org/2000/svg" height="13" width="13" viewBox="0 0 512 512" class="mr-1">
            <path
              d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM64 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L96 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
          </svg>
          Chapter
        </h4>
        <div class="pl-7">
          <p><span class="text-button">WRITE</span> tab</p>
          <p>Add Title, Location and Period, and tell a Story.</p>
          <p>
            At the bottom of the page You can upload images to display in your
            diary.
          </p>

          <p class="mt-3"><span class="text-button">PREVIEW</span> tab</p>
          <p>
            Allows to preview and change the layout of the page - how it will
            look like when printed or downloaded.
          </p>
        </div>

        <h4 class="mb-1 mt-6">
          <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 512" class="mr-1 mb-n1">
            <path
              d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-167l80 80c9.4 9.4 24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-39 39L344 184c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 134.1-39-39c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9z" />
          </svg>
          Download
        </h4>
        <div class="pl-7">
          Download Your diary as a PDF file so it can be printed later.
        </div>

        <h4 class="mb-1 mt-6">
          <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 512" class="mr-1 mb-n1">
            <path
              d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
          </svg>
          Print preview
        </h4>
        <div class="pl-7">
          <p>
            Allows to preview all the pages of Your diary just the same as it
            would look like when printed or downloaded.
          </p>
          <p>
            If you have a printer near by, then from this view You can send the
            pages directly to the printer.
          </p>
        </div>

        <h4 class="mb-1 mt-6">
          <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512" class="mr-1 mb-n1">
            <path
              d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
          </svg>
          Settings
        </h4>
        <div class="pl-7">
          Settings allow to change various parameters of how the printed or
          downloaded pages will look like.
        </div>
      </v-container>

      <template v-slot:actions>
        <v-btn class="ms-auto" text="Close" @click="localIsOpen = false"></v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>
