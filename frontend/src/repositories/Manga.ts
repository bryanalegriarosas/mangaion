import Api from "../services/Api";

export const getMangas = async () => {
  const { data } = await Api.get("/mangas");
  return data;
};

export const getManga = async (id: number) => {
  const { data } = await Api.get(`/mangas/${id}`);
  return data;
};
