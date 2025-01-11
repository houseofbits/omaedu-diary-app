<script setup>
import {
  onMounted,
  reactive,
  ref,
  inject,
  computed,
  nextTick,
  watch,
} from "vue";
import EditChapter from "./ChapterEditView.vue";
import Chapters from "./ChaptersLIstView.vue";
import PrintPreview from "./DiaryPrintView.vue";
import HealthRecordHeader from "../components/HealthRecordHeader.vue";
import {
  fetchDiary,
  fetchAllChapters,
  fetchChapter,
  postChapter,
  putChapter,
  deleteChapter,
} from "../api/api.js";
import { useTheme } from "vuetify";
import useErrorStack from "../composables/ErrorStack.js";
import _ from "lodash";
import useHealthRecordSettings from "../composables/HealthRecordSettings.js";

const { healthRecordSettings, setHealthRecordSettings } =
  useHealthRecordSettings();

const props = defineProps({
  diaryId: Number,
});

const emit = defineEmits(["close"]);

const { errorMessages, addErrorMessage } = useErrorStack();
const theme = useTheme();
const secondaryColor = theme.current.value.colors.secondary;
const userCredentials = inject("userCredentials");
const isLoading = ref(true);

onMounted(async () => {
  try {
    const data = await fetchDiary(userCredentials, props.diaryId);
    setHealthRecordSettings(data);
  } catch (error) {
    addErrorMessage(
      "Failed to load health record table. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  isLoading.value = false;
});
</script>

<template>
  <template v-if="!isLoading">
    <health-record-header
      :diary-id="diaryId"
      :can-delete="true"
      @close="emit('close')"
    ></health-record-header>
  </template>
</template>

<style>
.header-bg {
  /* background-image: var(--header-bg-image); */
  background-image: url(/assets/images/balti-ziedi.jpg);
  background-size: cover !important;
  /* background: linear-gradient(0deg, rgba(94, 90, 85, 0.2) 0%, rgb(201, 192, 181) 100%); */
}
</style>