<script setup>
import { ref, watch, inject, onMounted } from "vue";
import useDiarySettings from "../../composables/DiarySettings";
import { postDiary, putDiary } from "../../api/api";
import useErrorStack from "../../composables/ErrorStack.js";
import ColorInput from "../ColorInput.vue";

const { addErrorMessage } = useErrorStack();
const emit = defineEmits(["created", "update:modelValue"]);

const { diarySettings, getDiaryThemeOptions } = useDiarySettings();

const props = defineProps({
  modelValue: Boolean,
  diaryId: {
    type: Number,
    default: null,
  },
});

const userCredentials = inject("userCredentials");
const form = ref(null);
const localIsOpen = ref(props.modelValue);
const color = ref(diarySettings.color);
const titleText = ref(diarySettings.title);
const description = ref(diarySettings.description);
const isPageNumberingEnabled = ref(diarySettings.isPageNumberingEnabled);
const isTextJustifyEnabled = ref(diarySettings.isTextJustifyEnabled);
const diaryTheme = ref(diarySettings.diaryTheme);

function loadSettingsValues() {
  if (props.diaryId) {
    titleText.value = diarySettings.title;
    description.value = diarySettings.description;
    color.value = diarySettings.color;
    isPageNumberingEnabled.value = diarySettings.isPageNumberingEnabled;
    isTextJustifyEnabled.value = diarySettings.isTextJustifyEnabled;
    diaryTheme.value = diarySettings.diaryTheme;
  } else {
    titleText.value = "";
    description.value = "";
    color.value = diarySettings.color;
    isPageNumberingEnabled.value = true;
    isTextJustifyEnabled.value = true;
    diaryTheme.value = "White";
  }
}

watch(
  () => props.modelValue,
  () => {
    localIsOpen.value = props.modelValue;
    loadSettingsValues();
  }
);

watch(
  () => localIsOpen.value,
  () => {
    emit("update:modelValue", localIsOpen.value);
  }
);

function requiredValidationRule(value) {
  return !!value || "Required.";
}

function updateDiarySettings() {
  diarySettings.title = titleText.value;
  diarySettings.description = description.value;
  diarySettings.isPageNumberingEnabled = isPageNumberingEnabled.value;
  diarySettings.isTextJustifyEnabled = isTextJustifyEnabled.value;
  diarySettings.diaryTheme = diaryTheme.value;
  diarySettings.color = color.value;
}

async function create() {
  const { valid } = await form.value.validate();

  if (!valid) {
    return;
  }

  updateDiarySettings();

  try {
    const result = await postDiary(
      userCredentials,
      "diary",
      titleText.value,
      description.value,
      color.value,
      diarySettings
    );

    emit("created", result.diaryId);
  } catch (e) {
    addErrorMessage(
      "Failed to create diary. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  localIsOpen.value = false;
}

async function update() {
  const { valid } = await form.value.validate();

  if (!valid) {
    return;
  }

  updateDiarySettings();

  try {
    const result = await putDiary(
      userCredentials,
      props.diaryId,
      titleText.value,
      description.value,
      color.value,
      diarySettings
    );
  } catch (e) {
    addErrorMessage(
      "Failed to update diary. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  localIsOpen.value = false;
}
</script>

<template>
  <v-dialog
    v-model="localIsOpen"
    :fullscreen="$vuetify.display.xs"
    max-width="600"
  >
    <v-form ref="form">
      <v-card>
        <v-card-item>
          <div class="d-flex align-center justify-space-between">
          <v-card-title v-if="diaryId">Change diary attributes</v-card-title>
          <v-card-title v-else>Start a new diary</v-card-title>
          <color-input id="diary-color" v-model="color" />
        </div>
        </v-card-item>

        <v-card-text>
          <v-row dense>
            <v-text-field
              v-model="titleText"
              label="Title of this diary (required)"
              :rules="[requiredValidationRule]"
              variant="underlined"
              required
            />
          </v-row>
          <v-row dense class="mb-6">
            <v-textarea
              v-model="description"
              label="Diary description"
              row-height="4"
              rows="4"
              variant="underlined"
              auto-grow
              hide-details
            />
          </v-row>
          <v-row dense>
            <v-select
              v-model="diaryTheme"
              label="Select diary theme"
              hint="This setting affects how Your printed or downloaded diary looks"
              persistent-hint
              :items="getDiaryThemeOptions()"
            ></v-select>
          </v-row>
          <v-row dense>
            <v-switch
              v-model="isPageNumberingEnabled"
              hide-details
              label="Show diary page numbers when printing"
              color="primary"
            ></v-switch>
          </v-row>
          <v-row dense>
            <v-switch
              v-model="isTextJustifyEnabled"
              hide-details
              label="Justify text"
              color="primary"
            ></v-switch>
          </v-row>
        </v-card-text>

        <template v-slot:actions>
          <div class="d-flex justify-end">
            <v-btn
              text="Cancel"
              @click="localIsOpen = false"
              class="mr-2"
            ></v-btn>

            <v-btn v-if="diaryId !== null" text="Save" @click="update"></v-btn>
            <v-btn v-else class="ms-auto" text="Create" @click="create"></v-btn>
          </div>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<style scoped>
:deep(.v-card-item__content) {
  overflow: visible !important;
}
</style>