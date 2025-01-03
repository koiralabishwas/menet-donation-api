import Layout from "@/layout";

type Props = {
    project: string;
};

export default function Project({ project }: Props) {
    return (
        <Layout className="max-w-screen-lg mx-auto lg:pt-5">
            <img
                src="/images/altervoice.jpg"
                alt="Altervoice"
                className="w-full lg:rounded-xl lg:max-h-90"
            />
            <article className="p-2 pt-5 leading-10 prose max-w-none prose-h1:text-primary prose-h1:font-bold prose-h1:m-0">
                <h1>{project}</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Pellentesque et odio sed est placerat tincidunt. Donec
                    ullamcorper, nisl in commodo tincidunt, lorem nibh
                    pellentesque nisl, sed tincidunt elit nunc et elit. Sed
                    tincidunt, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Nullam
                    eget nisl in augue sodales posuere. Donec sed nisl
                    pellentesque, ultrices elit in, sodales mauris. Donec
                    hendrerit, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Nullam
                    eget nisl in augue sodales posuere. Donec sed nisl
                    pellentesque, ultrices elit in, sodales mauris. Donec
                    hendrerit, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit.
                    Pellentesque et odio sed est placerat tincidunt. Donec
                    ullamcorper, nisl in commodo tincidunt, lorem nibh
                    pellentesque nisl, sed tincidunt elit nunc et elit. Sed
                    tincidunt, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Nullam
                    eget nisl in augue sodales posuere. Donec sed nisl
                    pellentesque, ultrices elit in, sodales mauris. Donec
                    hendrerit, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Nullam
                    eget nisl in augue sodales posuere. Donec sed nisl
                    pellentesque, ultrices elit in, sodales mauris. Donec
                    hendrerit, nisl sed ultricies commodo, ipsum diam
                    pellentesque diam, a eleifend nisi mi euismod elit. Donec
                    ullamcorper, nisl in commodo tincidunt, lorem nibh
                    pellentesque nisl, sed tincidunt elit nunc et elit.
                </p>
            </article>
        </Layout>
    );
}
