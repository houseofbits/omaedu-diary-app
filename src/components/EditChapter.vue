<script setup>
import { ref, nextTick, watch } from "vue";
import { clone, set } from "lodash";
import EditChapterHeader from "./EditChapterHeader.vue";

const props = defineProps({
  chapter: Object,
});
const emit = defineEmits(["close", "update"]);
const tab = ref(null);
const titleText = ref(props.chapter.title);
const locationText = ref(props.chapter.location);
const dateText = ref(props.chapter.date);
const storyText = ref(props.chapter.story);

function emitUpdatedChapter() {
  const chapter = clone(props.chapter);

  set(chapter, "title", titleText.value);
  set(chapter, "date", dateText.value);
  set(chapter, "location", locationText.value);
  set(chapter, "story", storyText.value);

  emit("update", chapter);
}

watch(
  () => titleText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => locationText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => dateText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => storyText.value,
  () => {
    emitUpdatedChapter();
  }
);
</script>

<template>
  <edit-chapter-header
    :title="chapter.title"
    @close="emit('close')"
    @change-tab="(t) => (tab = t)"
  ></edit-chapter-header>

  <v-tabs-window v-model="tab" class="h-100">
    <v-tabs-window-item :key="1" :value="1">
      <v-sheet
        class="pa-4 text-center mx-auto"
        elevation="3"
        max-width="800"
        width="100%"
        height="100%"
      >
        <v-text-field
          v-model="titleText"
          label="Title of this chapter"
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
      </v-sheet>
    </v-tabs-window-item>

    <v-tabs-window-item :key="2" :value="2">
      <v-sheet
        class="pa-4 text-center mx-auto"
        elevation="3"
        max-width="800"
        width="100%"
        height="100%"
      >
      </v-sheet>
    </v-tabs-window-item>

    <v-tabs-window-item :key="3" :value="3">
      <v-responsive :aspect-ratio="0.707" class="border ma-8 pa-4 elevation-7">
        <div class="w-100 h-100 border-thin d-flex flex-wrap">
          <div
            style="display: grid; grid-template-columns: auto 1fr; gap: 10px"
          >
            <img src="https://picsum.photos/200" alt="Scenic View" />
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, </p>
          </div>
        </div>
      </v-responsive>
    </v-tabs-window-item>
  </v-tabs-window>
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
</style>