<script setup>
import { reactive, ref } from "vue";
import EditChapter from "./components/EditChapter.vue";
import Chapters from "./components/Chapters.vue";
import ChaptersHeader from "./components/ChaptersHeader.vue";

const chapters = reactive([]);

const selectedChapter = ref(null);

function selectChapter(index) {
  selectedChapter.value = index;
}

function addChapterAndEdit() {
  chapters.push({
    title: "Chapter title",
    date: "2023-12-21",
    location: "",
    story: "",
  });

  selectedChapter.value = chapters.length - 1;
}
function updateChapter(chapter) {
  chapters[selectedChapter.value] = chapter;
}
</script>

<template>
  <v-app>
    <edit-chapter
      v-if="selectedChapter !== null"
      :chapter="chapters[selectedChapter]"
      @close="selectedChapter = null"
      @update="updateChapter"
    ></edit-chapter>

    <template v-else>
      <chapters-header
        :is-chapters-empty="chapters.length == 0"
        @add-chapter="addChapterAndEdit"
      />
      <chapters :chapters="chapters" @select="selectChapter" />
    </template>

    
  </v-app>
</template>

