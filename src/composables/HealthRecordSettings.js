import { computed, reactive, ref } from "vue";

const healthRecordSettings = reactive({
    title: 'Health record table title',
    description: '',
    color: '#FFF',
    columns: [],
});


export default function useHealthRecordSettings() {

    function setHealthRecordSettings(settingsData) {
        healthRecordSettings.title = settingsData.title ?? 'Diary title';
        healthRecordSettings.color = settingsData.color ?? '#FFF';
        healthRecordSettings.description = settingsData.description ?? '';
        healthRecordSettings.columns = settingsData.settings.columns ?? [];
    }

    return {
        healthRecordSettings,
        setHealthRecordSettings,
    };
};