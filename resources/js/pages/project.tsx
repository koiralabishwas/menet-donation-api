import Layout from "@/layout";

type Props = {
    project: string;
};

export default function Project({ project }: Props) {
    return (
        <Layout className="p-2 mx-auto max-w-screen-2xl">
            <h1 className="text-3xl font-bold text-primary">{project}</h1>
        </Layout>
    );
}
