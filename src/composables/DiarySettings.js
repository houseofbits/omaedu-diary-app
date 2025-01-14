import { computed, reactive, ref } from "vue";

const diarySettings = reactive({
    title: 'Diary title',
    description: '',
    diaryTheme: 'White',
    isPageNumberingEnabled: true,
    isTextJustifyEnabled: true,
    color: '#FFF',
});

const diaryThemes = [
    {
        name: "White",
        backgoundImageUrl: null,
    },    
    {
        name: "Hills",
        backgoundImageUrl: "/assets/images/ainava.jpg",
    },
    {
        name: "Clouds",
        backgoundImageUrl: "/assets/images/makoni.jpg",
    },
    {
        name: "Fluffy",
        backgoundImageUrl: "/assets/images/rozigas-spalvas.jpg",
    },
    {
        name: "Feathers",
        backgoundImageUrl: "/assets/images/spalvas-roza.jpg",
    },
    {
        name: "Turquoise",
        backgoundImageUrl: "/assets/images/tirkizs.jpg",
    },
    {
        name: "Blue feathers",
        backgoundImageUrl: "/assets/images/zilas-spalvas.jpg",
    },
];

export default function useDiarySettings() {
    function getDiaryBackgroundImageUrl() {
        const index = diaryThemes.findIndex(theme => theme.name === settings.diaryTheme);
        if (index >= 0) {
            return diaryThemes[index].backgoundImageUrl;
        }

        return diaryThemes[0].backgoundImageUrl;
    }

    function getDiaryThemeOptions() {
        return diaryThemes.map(theme => theme.name);
    }

    function setDiarySettings(settingsData) {
        diarySettings.title = settingsData.title ?? 'Diary title';
        diarySettings.color = settingsData.color ?? '#FFF';
        diarySettings.description = settingsData.description ?? '';
        diarySettings.diaryTheme = settingsData.settings.diaryTheme ?? 'Default';
        diarySettings.isPageNumberingEnabled = settingsData.settings.isPageNumberingEnabled ?? true;
        diarySettings.isTextJustifyEnabled = settingsData.settings.isTextJustifyEnabled ?? true;
    }

    return {
        diarySettings,
        setDiarySettings,
        getDiaryThemeOptions,
        getDiaryBackgroundImageUrl
    };
};