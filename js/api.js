const API = "http://localhost:3001";

function getCookieValue(cookieName) {
  const cookies = document.cookie.split("; ");

  for (const cookie of cookies) {
    const [name, value] = cookie.split("=");
    console.log({ name });
    if (name === cookieName) {
      // Decode the cookie value
      const decodedValue = decodeURIComponent(value);

      // Parse as JSON if it's a JSON string
      try {
        return JSON.parse(decodedValue);
      } catch (error) {
        // If parsing fails, return the decoded value as is
        return decodedValue;
      }
    }
  }

  // Return null if the cookie is not found
  return null;
}

// Function to perform a generic HTTP request
async function fetchData(url, method, data = null, auth) {
  try {
    const options = {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: data ? JSON.stringify(data) : null,
    };

    if (auth) {
      const user = getCookieValue("storeeo_u");
      options.headers["user_token"] = user.user_token;
      if (data) {
        let shopUrl =
          window.location.protocol + "//" + window.location.hostname;
        const pathSegments = window.location.pathname
          .split("/")
          .filter(Boolean);
        const firstPathSegment = pathSegments.length > 0 ? pathSegments[0] : "";

        shopUrl =
          shopUrl === "http://localhost"
            ? shopUrl + "/" + firstPathSegment
            : shopUrl;
        options.body = JSON.stringify({ ...data, shop_url: shopUrl });
      }
    }

    console.log({ options });

    const response = await fetch(url, options);
    const result = await response.json();
    return result;
  } catch (error) {
    console.error(`Error in fetchData for ${url}`, error);
    throw error; // Re-throw the error to allow the calling function to handle it
  }
}

// Function to create a new record
async function createRecord(route, data, auth = true) {
  try {
    const url = `${API}/${route}/`;
    const method = "POST";
    return await fetchData(url, method, data, auth);
  } catch (error) {
    console.error(`Error in createRecord for ${API}`, error);
    throw error;
  }
}

// Function to update an existing record
async function updateRecord(id, data, auth = true) {
  try {
    const url = `${API}/${id}`;
    const method = "PUT";
    return await fetchData(url, method, data);
  } catch (error) {
    console.error(`Error in updateRecord for ${API}/${id}`, error);
    throw error;
  }
}

// Function to get a record by ID
async function getRecord(route, id = null, auth = true) {
  try {
    const url = id ? `${API}/${route}/${id}` : `${API}/${route}/`;
    const method = "GET";
    return await fetchData(url, method);
  } catch (error) {
    console.error(`Error in getRecord for ${API}/${id}`, error);
    throw error;
  }
}

// Function to delete a record by ID
async function deleteRecord(id, auth = true) {
  try {
    const url = `${API}/${id}`;
    const method = "DELETE";
    return await fetchData(url, method);
  } catch (error) {
    console.error(`Error in deleteRecord for ${API}/${id}`, error);
    throw error;
  }
}
