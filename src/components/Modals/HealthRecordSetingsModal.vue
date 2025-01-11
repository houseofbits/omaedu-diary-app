<script setup>
import { ref, watch, reactive, inject } from "vue";
import { useTheme } from "vuetify";
import { postDiary, putDiary } from "../../api/api";
import useErrorStack from "../../composables/ErrorStack.js";
import useHealthRecordSettings from "../../composables/HealthRecordSettings.js";

const { addErrorMessage } = useErrorStack();
const emit = defineEmits(["created", "update:modelValue"]);

const props = defineProps({
  modelValue: Boolean,
  diaryId: {
    type: Number,
    default: null,
  },
});

const theme = useTheme();

const { healthRecordSettings, setHealthRecordSettings } =
  useHealthRecordSettings();

const userCredentials = inject("userCredentials");
const form = ref(null);
const primaryColor = theme.current.value.colors.primary;
const localIsOpen = ref(props.modelValue);
const titleText = ref("");
const hasMissingColumns = ref(false);
const description = ref("");
const columnNames = ["Date", "Weight", "Height", "Blood pressure"];
const columnTypes = ["Text", "Date"];
const columns = reactive([]);

function loadSettingsValues() {
  if (props.diaryId) {
    titleText.value = healthRecordSettings.title;
    description.value = healthRecordSettings.description;
    columns.length = 0;
    columns.push(...healthRecordSettings.columns);
  } else {
    titleText.value = "";
    description.value = "";
    columns.length = 0;
  }
}

function requiredValidationRule(value) {
  return !!value || "Required.";
}

watch(
  () => columns,
  () => {
    hasMissingColumns.value = false;
  },
  { deep: true }
);

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

function addColumn() {
  columns.push({
    title: "",
    type: "",
  });
}

function deleteColumn(index) {
  columns.splice(index, 1);
}

async function create() {
  const { valid } = await form.value.validate();
  hasMissingColumns.value = columns.length == 0;

  if (!valid || hasMissingColumns.value) {
    return;
  }

  try {
    const result = await postDiary(
      userCredentials,
      "health-record",
      titleText.value,
      description.value,
      {
        columns: columns,
      }
    );

    emit("created", result.diaryId);
  } catch (e) {
    addErrorMessage(
      "Failed to create health record table. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  localIsOpen.value = false;
}

async function update() {
  const { valid } = await form.value.validate();
  hasMissingColumns.value = columns.length == 0;

  if (!valid || hasMissingColumns.value) {
    return;
  }

  try {
    const result = await putDiary(
      userCredentials,
      props.diaryId,
      titleText.value,
      description.value,
      {
        columns: columns,
      }
    );

    setHealthRecordSettings({
      title: titleText.value,
      description: description.value,
      settings: {
        columns: Object.values(columns),
      },
    });
  } catch (e) {
    addErrorMessage(
      "Failed to update health record table. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  localIsOpen.value = false;
}
</script>

<template>
  <v-dialog v-model="localIsOpen" width="auto">
    <v-form ref="form">
      <v-card min-width="500">
        <v-card-item>
          <v-card-title v-if="diaryId">
            Change health record table attributes
          </v-card-title>
          <v-card-title v-else>Add new health record table</v-card-title>
        </v-card-item>

        <v-card-text class="mb-3 pb-0">
          <v-row dense>
            <v-text-field
              v-model="titleText"
              label="Title of this diary (required)"
              :rules="[requiredValidationRule]"
              variant="underlined"
            />
          </v-row>
          <v-row dense>
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
        </v-card-text>

        <v-card-text>
          <div class="text-h6 mb-3">Table columns</div>

          <v-row dense v-for="(column, index) in columns" :key="index">
            <v-col cols="6" class="flex-grow-1 flex-shrink-0">
              <v-combobox
                v-model="column.title"
                label="Title"
                density="compact"
                :items="columnNames"
                variant="underlined"
                :rules="[requiredValidationRule]"
              ></v-combobox
            ></v-col>
            <v-col class="flex-grow-1 flex-shrink-0">
              <v-select
                v-model="column.type"
                label="Type"
                density="compact"
                :items="columnTypes"
                variant="underlined"
                :rules="[requiredValidationRule]"
              ></v-select>
            </v-col>
            <v-col class="flex-grow-0 flex-shrink-1">
              <v-btn
                color="medium-emphasis"
                icon="mdi-bookmark"
                size="small"
                @click="deleteColumn(index)"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="18"
                  width="18"
                  viewBox="0 0 448 512"
                >
                  <path
                    :fill="primaryColor"
                    d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
                  />
                </svg>
              </v-btn>
            </v-col>
          </v-row>

          <v-row dense>
            <div v-if="hasMissingColumns" class="text-body-2 text-error">
              Please add table columns
            </div>

            <v-btn class="ms-auto" @click="addColumn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="20"
                width="20"
                viewBox="0 0 448 512"
                class="mr-2"
              >
                <path
                  :fill="primaryColor"
                  d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"
                />
              </svg>
              <span>Add column</span>
            </v-btn>
          </v-row>

          <v-row
            v-if="diaryId && columns.length > 0"
            dense
            class="text-caption mb-3"
          >
            Removing table column cannot be undone.
          </v-row>
        </v-card-text>

        <template v-slot:actions>
          <v-btn
            v-if="diaryId !== null"
            class="ms-auto"
            text="Save"
            @click="update"
          ></v-btn>
          <v-btn v-else class="ms-auto" text="Create" @click="create"></v-btn>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

