<script setup>
import { ref, reactive, watch, onMounted, inject, computed } from "vue";
import { clone, set } from "lodash";
import EditChapterHeader from "./EditChapterHeader.vue";
import PagePreview from "./PagePreview.vue";
import { useTheme } from "vuetify";
import {
  fetchChapterImages,
  deleteChapterImage,
  postChapterImage,
  imageUrl,
} from "../api/api.js";
import _ from "lodash";
import useErrorStack from "../composables/ErrorStack";

const { addErrorMessage } = useErrorStack();
const userCredentials = inject("userCredentials");
const theme = useTheme();
const primaryColor = theme.current.value.colors.primary;
const props = defineProps({
  chapter: Object,
});
const emit = defineEmits(["close", "update", "delete"]);
const tab = ref(null);
const fileInput = ref(null);
const titleText = ref(props.chapter.title);
const locationText = ref(props.chapter.location);
const dateText = ref(props.chapter.period);
const storyText = ref(props.chapter.story);
const layouts = ref(props.chapter.layouts);
const images = reactive([]);

function emitUpdatedChapter() {
  const chapter = clone(props.chapter);

  set(chapter, "title", titleText.value);
  set(chapter, "period", dateText.value);
  set(chapter, "location", locationText.value);
  set(chapter, "story", storyText.value);
  set(chapter, "layouts", layouts.value);

  emit("update", chapter);
}

const handleUpdateDebounced = _.debounce(emitUpdatedChapter, 1);

function requiredValidationRule(value) {
  return !!value || "Required.";
}

function triggerUpload() {
  fileInput.value.click();
}

async function deleteImage(id) {
  try {
    await deleteChapterImage(userCredentials, id);
    const index = images.findIndex((image) => image.id === id);
    if (index >= 0) {
      images.splice(index, 1);
    }
  } catch (error) {    
    addErrorMessage(
      "Failed to remove image. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

async function uploadImage() {
  try {
    const imageData = await postChapterImage(
      userCredentials,
      props.chapter.id,
      fileInput.value.files[0]
    );

    images.push(imageData);
  } catch (error) {
    addErrorMessage(
      "Failed to upload image. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

function getImageUrl(imageId) {
  return imageUrl(userCredentials, imageId);
}

function updateLayouts(layoutsData) {
  layouts.value = layoutsData;
  handleUpdateDebounced();
}

watch(
  () => titleText.value,
  () => {
    handleUpdateDebounced();
  }
);
watch(
  () => locationText.value,
  () => {
    handleUpdateDebounced();
  }
);
watch(
  () => dateText.value,
  () => {
    handleUpdateDebounced();
  }
);
watch(
  () => storyText.value,
  () => {
    handleUpdateDebounced();
  }
);

const imageUrls = computed(() => {
  return images.map((image) => {
    return imageUrl(userCredentials, image.id);
  });
});

onMounted(async () => {
  try {
    const imagesData = await fetchChapterImages(
      userCredentials,
      props.chapter.id
    );
    images.length = 0;
    images.push(...imagesData);
  } catch (error) {
    addErrorMessage(
      "Failed to load chapter images. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
});
</script>

<template>
  <edit-chapter-header
    :title="chapter.title"
    @close="emit('close')"
    @change-tab="(t) => (tab = t)"
    @delete="emit('delete', chapter.id)"
  ></edit-chapter-header>

  <v-tabs-window v-model="tab" class="h-100">
    <v-tabs-window-item :key="1" :value="1">
      <v-sheet
        class="rounded-lg mt-4 pa-4 text-center mx-auto d-flex flex-column"
        elevation="4"
        max-width="800"
        width="100%"
        height="100%"
      >
        <v-text-field
          v-model="titleText"
          label="Title of this chapter (required)"
          :rules="[requiredValidationRule]"
          variant="underlined"
        />

        <v-text-field
          v-model="locationText"
          label="Location"
          variant="underlined"
        />

        <v-text-field v-model="dateText" label="Period" variant="underlined" />

        <v-textarea
          v-model="storyText"
          label="Your story"
          row-height="30"
          rows="4"
          variant="underlined"
          auto-grow
          hide-details
        />

        <v-table>
          <tbody>
            <tr v-for="image in images" :key="image.id">
              <td class="td-icon">
                <div class="d-flex align-center justify-end">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24"
                    width="24"
                    viewBox="0 0 512 512"
                  >
                    <path
                      :fill="primaryColor"
                      d="M0 96C0 60.7 28.7 32 64 32l384 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zM323.8 202.5c-4.5-6.6-11.9-10.5-19.8-10.5s-15.4 3.9-19.8 10.5l-87 127.6L170.7 297c-4.6-5.7-11.5-9-18.7-9s-14.2 3.3-18.7 9l-64 80c-5.8 7.2-6.9 17.1-2.9 25.4s12.4 13.6 21.6 13.6l96 0 32 0 208 0c8.9 0 17.1-4.9 21.2-12.8s3.6-17.4-1.4-24.7l-120-176zM112 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"
                    />
                  </svg>
                </div>
              </td>
              <td class="text-left">
                {{ image.fileName }}
              </td>
              <td>
                <div class="d-flex align-center justify-end">
                  <v-btn
                    class="px-2"
                    min-width="20"
                    rounded="xl"
                    @click="deleteImage(image.id)"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="20"
                      width="17.5"
                      viewBox="0 0 448 512"
                    >
                      <path
                        :fill="primaryColor"
                        d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
                      />
                    </svg>
                  </v-btn>
                </div>
              </td>
            </tr>
          </tbody>
        </v-table>

        <v-btn class="mt-4" @click="triggerUpload">Upload image</v-btn>
      </v-sheet>
    </v-tabs-window-item>

    <v-tabs-window-item :key="2" :value="2">
      <page-preview :chapter="chapter" :images="imageUrls" @change-layouts="updateLayouts" />
    </v-tabs-window-item>
  </v-tabs-window>

  <input type="file" ref="fileInput" id="file-upload" @change="uploadImage" />
</template>

<style>
.editable-text-frame {
  width: 100%;
  height: 100%;
}
.img {
  position: inline;
  width: 100px;
  height: 100px;
  border: 1px solid red;
  bottom: 0px;
}
#file-upload {
  visibility: hidden;
}
.td-icon {
  width: 40px;
}
</style>