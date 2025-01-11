import { computed, reactive, ref } from "vue";

const healthRecordSettings = reactive({
    title: 'Health record table title',
    description: '',
    columns: [],
});


export default function useHealthRecordSettings() {

    function setHealthRecordSettings(settingsData) {
        healthRecordSettings.title = settingsData.title ?? 'Diary title';
        healthRecordSettings.description = settingsData.description ?? '';
        healthRecordSettings.columns = settingsData.settings.columns ?? [];
    }

    return {
        healthRecordSettings,
        setHealthRecordSettings,
    };
};