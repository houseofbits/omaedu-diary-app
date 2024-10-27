const API_ENDPOINT = "api.php";

const abortControllers = [];


export async function fetchUser() {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "user",
      credentials: "base64encodedstr",
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function fetchAllChapters() {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: "base64encodedstr",
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function fetchChapter(id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: "base64encodedstr",
      id
    }).toString(),
    {
      method: "GET",
    }
  );

  return await response.json();
}

export async function deleteChapter(id) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: "base64encodedstr",
      id
    }).toString(),
    {
      method: "DELETE",
    }
  );

  return await response.json();
}

export async function postChapter(chapter) {
  const response = await fetch(
    API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: "base64encodedstr",
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

export async function putChapter(chapter) {

  const url = API_ENDPOINT +
    "?" +
    new URLSearchParams({
      target: "chapters",
      credentials: "base64encodedstr",
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

  }
}

