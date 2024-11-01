import { computed, reactive, ref } from "vue";


export default function useErrorStack() {

    const errorMessages = reactive([]);
    let lastMessageId = 0;

    function addErrorMessage(message) {
        errorMessages.push({
            id: lastMessageId,
            text: message
        });
        const id = lastMessageId;
        setTimeout(() => {
            removeMessage(id);
        }, 5000);

        lastMessageId++;
    }

    function removeMessage(id) {
        const index = errorMessages.findIndex((message) => {
            return message.id == id;
        });

        if (index >= 0) {
            errorMessages.splice(index, 1);
        }
    }

    return {
        errorMessages,
        addErrorMessage
    };
};