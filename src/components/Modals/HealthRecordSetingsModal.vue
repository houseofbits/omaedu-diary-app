<script setup>
import { ref, watch, reactive, inject } from "vue";
import { useTheme } from "vuetify";
import { postDiary, putDiary } from "../../api/api";
import useErrorStack from "../../composables/ErrorStack.js";
import useHealthRecordSettings from "../../composables/HealthRecordSettings.js";
import MD5 from "crypto-js/md5";
import _ from "lodash";
import ColorInput from "../ColorInput.vue";

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
const color = ref("#FFF");
const localIsOpen = ref(props.modelValue);
const titleText = ref("");
const hasMissingColumns = ref(false);
const description = ref("");
const columnNames = ["Date", "Weight", "Height", "Blood pressure"];
const columnTypes = ["Text", "Date", "Time", "Date and time"];
const columns = reactive([]);
const isDeletionWarningVisible = ref(false);

function loadSettingsValues() {
  if (props.diaryId) {
    titleText.value = healthRecordSettings.title;
    color.value = healthRecordSettings.color;
    description.value = healthRecordSettings.description;
    columns.length = 0;
    columns.push(..._.cloneDeep(healthRecordSettings.columns));
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
    identifier: MD5(performance.now().toString()).toString(),
    title: "",
    type: "",
  });
}

function deleteColumn(index) {
  columns.splice(index, 1);
  if (props.diaryId) {
    isDeletionWarningVisible.value = true;
  }
}

function canMoveUp(index) {
  return index > 0;
}

function canMoveDown(index) {
  return index < columns.length - 1;
}

function moveUp(index) {
  if (index > 0) {
    [columns[index], columns[index - 1]] = [columns[index - 1], columns[index]];
  }
}

function moveDown(index) {
  if (index < columns.length - 1) {
    [columns[index + 1], columns[index]] = [columns[index], columns[index + 1]];
  }
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
      color.value,
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
      color.value,
      {
        columns: columns,
      }
    );

    setHealthRecordSettings({
      title: titleText.value,
      description: description.value,
      color: color.value,
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
  <v-dialog
    v-model="localIsOpen"
    :fullscreen="$vuetify.display.xs"
    max-width="600"
  >
    <v-form ref="form">
      <v-card>
        <v-card-item>
          <div class="d-flex align-center justify-space-between">
            <v-card-title v-if="diaryId">
              Change health record table attributes
            </v-card-title>
            <v-card-title v-else>Add new health record table</v-card-title>
            <color-input id="hr-color" v-model="color" />
          </div>
        </v-card-item>

        <v-card-text class="mb-3 pb-0">
          <v-row dense>
            <v-text-field
              v-model="titleText"
              label="Title of this health record table (required)"
              :rules="[requiredValidationRule]"
              variant="underlined"
            />
          </v-row>
          <v-row dense>
            <v-textarea
              v-model="description"
              label="Health record table description"
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
                :id="'menu-activator' + index"
                color="medium-emphasis"
                icon="mdi-bookmark"
                size="small"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  height="18"
                  width="18"
                  viewBox="0 0 512 512"
                >
                  <path
                    :fill="primaryColor"
                    d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"
                  />
                </svg>
              </v-btn>

              <v-menu :activator="'#menu-activator' + index">
                <v-list class="pa-0">
                  <v-list-item density="compact" class="pa-0 ma-0">
                    <v-btn
                      variant="text"
                      class="pa-2"
                      size="small"
                      block
                      @click="moveUp(index)"
                      :disabled="!canMoveUp(index)"
                      >Move up</v-btn
                    >
                  </v-list-item>
                  <v-list-item density="compact" class="pa-0 ma-0">
                    <v-btn
                      variant="text"
                      class="pa-2"
                      size="small"
                      block
                      @click="moveDown(index)"
                      :disabled="!canMoveDown(index)"
                      >Move down</v-btn
                    >
                  </v-list-item>
                  <v-list-item density="compact" class="pa-0 ma-0">
                    <v-btn
                      variant="text"
                      class="pa-2"
                      size="small"
                      block
                      @click="deleteColumn(index)"
                      >Delete</v-btn
                    >
                  </v-list-item>
                </v-list>
              </v-menu>
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
            v-if="isDeletionWarningVisible"
            dense
            class="text-caption mb-3"
          >
            Removing table column cannot be undone.
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