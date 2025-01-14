<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  id: String,
  modelValue: { String },
  buttonClass: String,
});
const emit = defineEmits(["update:modelValue"]);

const isMenuOpen = ref(false);
const color = ref(props.modelValue);

const swatches = [
  ["FEE0F3", "FDC6E6", "FFB7E1"],
  ["FEDBD5", "FFC7C6", "FFACAB"],
  ["FEFADC", "FFF6BA", "FFF29C"],  
  ["E9FFF3", "D1FFE9", "C2FFE1"],    
  ["E6F4FF", "D4ECFF", "C5E5FE"],      
];

function close() {
  isMenuOpen.value = false;
}

watch(
  () => color.value,
  () => {
    emit("update:modelValue", color.value);
  }
);
</script>

<template>
  <v-btn
    :id="'menu-activator-' + id"
    variant="elevated"
    class="d-flex justify-start"
    size="45"
    :color="color"
    rounded="xl"
    :class="buttonClass"
    elevation="3"
  >
  </v-btn>

  <v-menu
    v-model="isMenuOpen"
    :activator="'#menu-activator-' + id"
    :close-on-content-click="false"
  >
    <v-card>
      <v-confirm-edit v-model="color" @save="close" @cancel="close">
        <template v-slot:default="{ model: proxyModel, actions }">
          <v-color-picker
            mode="rgb"
            :modes="['rgb']"
            v-model="proxyModel.value"
            hide-inputs
            elevation="0"
            show-swatches
            :swatches="swatches"
          />
          <v-card-actions class="d-flex justify-end">
            <component :is="actions"></component>
          </v-card-actions>
        </template>
      </v-confirm-edit>
    </v-card>
  </v-menu>
</template>
