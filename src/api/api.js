const API_ENDPOINT = "api.php";

const abortControllers = [];


export async function fetchUser(userCredentials) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "user",
      credentials: userCredentials,
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function updateUser(userCredentials, userData) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "user",
      credentials: userCredentials,
    }).toString(),
    {
      method: "POST",
      body: JSON.stringify(userData),
      headers: {
        'Content-Type': 'application/json'
      }
    }
  );

  return await response.json();
}

export async function fetchAllChapters(userCredentials, diaryId) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: userCredentials,
      id: diaryId,
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function fetchChapter(userCredentials, id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapter",
      credentials: userCredentials,
      id
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function deleteChapter(userCredentials, id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapter",
      credentials: userCredentials,
      id
    }).toString(),
    {
      method: "DELETE",
    }
  );

  return await response.json();
}

export async function postChapter(userCredentials, chapter) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapter",
      credentials: userCredentials,
    }).toString(),
    {
      method: "POST",
      body: JSON.stringify(chapter),
      headers: {
        'Content-Type': 'application/json'
      }
    }
  );

  return await response.json();
}

export async function putChapter(userCredentials, chapter) {

  const url = API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapter",
      credentials: userCredentials,
      id: chapter.id,
    }).toString();

  abortControllers[url]?.abort();
  abortControllers[url] = new AbortController();

  try {
    const response = await fetch(
      url,
      {
        method: "PUT",
        body: JSON.stringify(chapter),
        headers: {
          'Content-Type': 'application/json'
        },
        signal: abortControllers[url].signal,
      }
    );

    return await response.json();
  } catch (error) {
    if (error.name !== 'AbortError') {
      throw error;
    }
  }
}

export async function postChapterImage(userCredentials, chapterId, file) {

  const url = API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "images",
      credentials: userCredentials,
      id: chapterId,
    }).toString();

  var data = new FormData();
  data.append('file', file);

  const response = await fetch(
    url,
    {
      method: "POST",
      body: data,
    }
  );

  return await response.json();
}

export async function deleteChapterImage(userCredentials, imageId) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "images",
      credentials: userCredentials,
      id: imageId
    }).toString(),
    {
      method: "DELETE",
    }
  );

  return await response.json();
}

export async function fetchChapterImages(userCredentials, chapterId) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "images",
      credentials: userCredentials,
      id: chapterId
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export function imageUrl(userCredentials, imageId) {
  return API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "img",
      credentials: userCredentials,
      id: imageId,
    }).toString();
}

export async function postDiary(userCredentials, type, title, description, settings) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "diaries",
      credentials: userCredentials,
    }).toString(),
    {
      method: "POST",
      body: JSON.stringify({
        type,
        title,
        description,
        settings
      }),
      headers: {
        'Content-Type': 'application/json'
      }
    }
  );

  return await response.json();
}

export async function putDiary(userCredentials, diaryId, title, description, settings) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "diaries",
      credentials: userCredentials,
      id: diaryId,
    }).toString(),
    {
      method: "PUT",
      body: JSON.stringify({
        title,
        description,
        settings
      }),
      headers: {
        'Content-Type': 'application/json'
      }
    }
  );

  return await response.json();
}


export async function fetchDiary(userCredentials, id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "diaries",
      credentials: userCredentials,
      id
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function fetchAllDiaries(userCredentials) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "diaries",
      credentials: userCredentials,
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function deleteDiary(userCredentials, id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "diaries",
      credentials: userCredentials,
      id
    }).toString(),
    {
      method: "DELETE",
    }
  );

  return await response.json();
}

export async function fetchAllHealthRecords(userCredentials, diaryId) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "health-records",
      credentials: userCredentials,
      id: diaryId,
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function postHealthRecord(userCredentials, diaryId, data) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "health-records",
      credentials: userCredentials,
    }).toString(),
    {
      method: "POST",
      body: JSON.stringify({
        diaryId, data
      }),
      headers: {
        'Content-Type': 'application/json'
      }
    }
  );

  return await response.json();
}

export async function deleteHealthRecord(userCredentials, id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "health-records",
      credentials: userCredentials,
      id
    }).toString(),
    {
      method: "DELETE",
    }
  );

  return await response.json();
}

export async function putHealthRecord(userCredentials, id, data) {

  const url = API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "health-records",
      credentials: userCredentials,
      id,
    }).toString();

  abortControllers[url]?.abort();
  abortControllers[url] = new AbortController();

  try {
    const response = await fetch(
      url,
      {
        method: "PUT",
        body: JSON.stringify(data),
        headers: {
          'Content-Type': 'application/json'
        },
        signal: abortControllers[url].signal,
      }
    );

    return await response.json();
  } catch (error) {
    if (error.name !== 'AbortError') {
      throw error;
    }
  }
}
