<script setup>
import { ref, watch } from "vue";
import useSettings from "../composables/Settings";

const props = defineProps({
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const { settings, getPageThemeOptions, getDiaryThemeOptions } = useSettings();

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
  <v-dialog v-model="localIsOpen" width="auto">
    <v-card
      title="Settings"
    >
      <v-card-text>
        <!-- <v-row dense>
          <v-col>
            <v-select
              v-model="settings.pageTheme"
              label="Select page theme"
              hint="This setting affects how this overall page looks and feels"
              persistent-hint
              :items="getPageThemeOptions()"
            ></v-select>
          </v-col>
        </v-row> -->
        <v-row dense>
          <v-col>
            <v-select
              v-model="settings.diaryTheme"
              label="Select diary theme"
              hint="This setting affects how Your printed or downloaded diary looks"
              persistent-hint
              :items="getDiaryThemeOptions()"
            ></v-select>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col>
            <v-switch
              v-model="settings.isPageNumberingEnabled"
              hide-details
              label="Show diary page numbers"
              color="primary"
            ></v-switch>
            <v-switch
              v-model="settings.isTextJustifyEnabled"
              hide-details
              label="Justify text"
              color="primary"
            ></v-switch>
          </v-col>
        </v-row>
      </v-card-text>

      <template v-slot:actions>
        <v-btn
          class="ms-auto"
          text="Close"
          @click="localIsOpen = false"
        ></v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

