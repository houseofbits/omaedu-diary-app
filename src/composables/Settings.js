import { computed, reactive, ref } from "vue";

const settings = reactive({
    pageTheme: 'Flowers',
    diaryTheme: 'White',
    isPageNumberingEnabled: true,
    isTextJustifyEnabled: true,
});

const pageThemes = [
    {
        name: "Flowers",
        backgoundImageUrl: "/assets/images/balti-ziedi.jpg",
    },
    {
        name: "Fluffy",
        backgoundImageUrl: "/assets/images/gaisas-spalvas.jpg",
    },
    {
        name: "Salmon",
        backgoundImageUrl: "/assets/images/lashkrasa.jpg",
    },
    {
        name: "Pastel",
        backgoundImageUrl: "/assets/images/pasteltoni.jpg",
    },
    {
        name: "Meadow",
        backgoundImageUrl: "/assets/images/zalganzils.jpg",
    },
    {
        name: "Feathers",
        backgoundImageUrl: "/assets/images/zilganas-spalvas.jpg",
    },
];

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

export default function useSettings() {

    function getPageBackgroundImageUrl() {
        const index = pageThemes.findIndex(theme => theme.name === settings.pageTheme);
        if (index >= 0) {
            return pageThemes[index].backgoundImageUrl;
        }

        return pageThemes[0].backgoundImageUrl;
    }

    function getDiaryBackgroundImageUrl() {
        const index = diaryThemes.findIndex(theme => theme.name === settings.diaryTheme);
        if (index >= 0) {
            return diaryThemes[index].backgoundImageUrl;
        }

        return diaryThemes[0].backgoundImageUrl;
    }

    function getPageThemeOptions() {
        return pageThemes.map(theme => theme.name);
    }

    function getDiaryThemeOptions() {
        return diaryThemes.map(theme => theme.name);
    }

    function setSettings(settingsData) {
        settings.pageTheme = settingsData.pageTheme ?? 'Default';
        settings.diaryTheme = settingsData.diaryTheme ?? 'Default';
        settings.isPageNumberingEnabled = settingsData.isPageNumberingEnabled ?? true;
        settings.isTextJustifyEnabled = settingsData.isTextJustifyEnabled ?? true;
    }

    return {
        settings,
        setSettings,
        getPageThemeOptions,
        getDiaryThemeOptions,
        getPageBackgroundImageUrl,
        getDiaryBackgroundImageUrl
    };
};