import Api from "../services/Api";

export const getMangas = async () => {
  const { data } = await Api.get("/mangas");
  return data;
};

export const getManga = async (slug: string) => {
  const { data } = await Api.get(`/mangas/${slug}`);
  return data;
};
